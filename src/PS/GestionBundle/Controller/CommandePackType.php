<?php

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PS\GestionBundle\Entity\Abonnement;
use PS\ParametreBundle\Entity\Pack;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommandePackType extends AbstractType
{
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pack', EntityType::class, [
            'class' => Pack::class, 'choice_label' => function ($pack) {
                return $pack->getLibelle() . ' - ' . $pack->getPrix() . ' FCFA ';
            }, 'label' => 'Pack', 'query_builder' => function (EntityRepository $e) use ($options) {
                $pack = $options['pack'];
                $qb = $e->createQueryBuilder('p');
                $where = ['PACK_CARD', 'PACK_BRACELET'];
                if ($pack->getAlias() == 'PACK_CARD') {
                    $where = ['PACK_BRACELET', 'PACK_SUBSCRIPTION'];
                } elseif (in_array($pack->getAlias(), ['PACK_SUBSCRIPTION', 'PACK_CARD'])) {
                    $where = ['PACK_SUBSCRIPTION'];
                }
                return $qb->andWhere($qb->expr()->in('p.alias', $where));
            },
            'choice_attr' => function ($pack) {
                return ['data-price' => $pack->getPrix()];
            },
            'attr' => ['readonly' => $this->session->has('commande')],
            'constraints' => [
                new NotBlank(['message' => 'Veuillez sélectionner un pack'])
            ]
        ])
            ->add('identifiant', null, ['attr' => ['readonly' => true]])
            ->add('pin', null, ['attr' => ['readonly' => true]])
            ->add('contact', null, ['label' => 'Numéro YUP']);
        if ($this->session->get('commande')) {
            $builder->add('code', null, ['label' => 'Code de confirmation YUP']);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,


        ]);

        $resolver->setRequired('pack');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_gestionbundle_abonnement';
    }
}
