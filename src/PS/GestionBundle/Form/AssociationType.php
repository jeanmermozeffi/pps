<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PS\GestionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AssociationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('identifiant', TextType::class, ['label' => 'Email Parent']);
        $builder->add('identifiants', CollectionType::class, [
            // each entry in the array will be an "email" field
            'entry_type'    => TextType::class,
            'allow_add'     => true,
            'allow_delete'  => true,
            'label'         => false,
            // these options are passed to each "email" type
            'entry_options' => [
                'attr' => ['class' => 'id-box'],
            ],
        ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            //'intention'  => 'change_password',
        ]);
    }

    public function getName()
    {
        return 'app_compte_associe_association';
    }
}
