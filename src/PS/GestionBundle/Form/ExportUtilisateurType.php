<?php

namespace PS\GestionBundle\Form;

use PS\GestionBundle\Form\Type\RoleChoiceType;
use PS\ParametreBundle\Entity\Fichier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ExportUtilisateurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'export.form.file', 'constraints' => [

                    new File(
                        [
                            'mimeTypes' => [
                                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv', 'text/plain', 'text/x-csv'
                            ],
                            'mimeTypesMessage' => 'Seules les extensions .xls et .xlsx, .csv sont autorisées'
                        ]
                    ),
                ]
            ])
            ->add('startCell', IntegerType::class, [
                'label' => 'De la ligne',
                'required' => false,
            ])
            ->add('endCell', IntegerType::class, [
                'label' => 'à la ligne',
                'required' => false,
            ])
            ->add('roles', RoleChoiceType::class, [
                'label' => 'Rôles',
                'required' => false,
                'choices'           => array_flip([
                    // 'ROLE_INFIRMIER'       => 'Infirmier',
                    // 'ROLE_MEDECIN'         => 'Medecin',
                    'ROLE_ADMIN_SUP'       => 'AGENT ENROLEUR',
                    'ROLE_ADMIN'           => 'ADMINISTRATION DSI',
                    // 'ROLE_ADMIN_CORPORATE' => 'Administrateur groupe médical',
                    // 'ROLE_ADMIN_LOCAL'     => 'Administrateur local',
                    // 'ROLE_PHARMACIE'       => 'Pharmacien',
                    // 'ROLE_SUPER_ADMIN'     => 'Super Administrateur',
                    // 'ROLE_RECEPTION'       => 'Réceptionniste',
                ]),
                'choices_as_values' => true,
                'expanded'          => false,
                'multiple'          => false,
                'placeholder' => "Veuillez indiquer le rôle des utilisateurs.",
                'attr' => ['class' => 'list-roles select2 select2-hopital'],
            ]);
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
