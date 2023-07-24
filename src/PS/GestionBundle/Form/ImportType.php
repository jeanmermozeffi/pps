<?php

namespace PS\GestionBundle\Form;

use PS\ParametreBundle\Entity\Fichier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => false
                , 'constraints' => [
                    
                    new File(
                        [
                            'mimeTypes' => [
                                'application/vnd.ms-excel'
                                , 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                , 'text/csv'
                                , 'text/plain'
                                , 'text/x-csv'
                            ],
                            'mimeTypesMessage' => 'Seules les extensions .xls et .xlsx sont autorisées'
                        ]
                    ),
                ]]);
            /*->add('hopital')
            ->add('corporate')*/
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => Fichier::class,
        ]);
    }

}
