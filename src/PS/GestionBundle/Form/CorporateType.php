<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Entity\Corporate;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityRepository;

class CorporateType extends AbstractType
{

    /**
     * @var mixed
     */
    private $token;

    /**
     * @param TokenStorage $token
     */
    public function __construct(TokenStorage $token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('raisonSociale');
        $builder->add('logo', FileType::class, ['required' => false, 'data_class' => null]);
        $builder->add('contact', TextType::class, ['required' => false]);
        $builder->get('contact')->addModelTransformer(
            new CallbackTransformer(
                function ($contact) {
                    return (string) $contact;
                },
                function ($contact) {
                    return (string) $contact;
                }
            )
        );


        /*$builder->add('corporateParent', EntityType::class
                , [
                    'required' => false
                    , 'class' => Corporate::class
                    , 'property' => 'raisonSociale'
                    , 'empty_value' => '--------',
                    'label'    => 'Corporate Parent',

                ]);*/

        if (!$this->token->getToken()->getUser()->getPersonne()->getCorporate()) {
            $builder->add('corporateParent', EntityType::class
                , [
                    'required' => false
                    , 'class' => Corporate::class
                    , 'property' => 'raisonSociale'
                    , 'empty_value' => '--------',
                    'label'    => 'Corporate Parent',
                    'query_builder' => function (EntityRepository $e) use($options) {
                        $qb = $e->createQueryBuilder('a');
                        if ($options['corporate']) {
                            $qb->andWhere('a.id <> :corporate');
                            $qb->setParameter('corporate', $options['corporate']);
                        }

                        return $qb;
                    }

                ]);
        }

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'PS\GestionBundle\Entity\Corporate',
            'corporate' => null
        ]);

        $resolver->setRequired('corporate');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_corporate';
    }

}
