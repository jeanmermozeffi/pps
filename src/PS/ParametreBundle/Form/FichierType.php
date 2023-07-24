<?php

namespace PS\ParametreBundle\Form;

use PS\ParametreBundle\Entity\Fichier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class FichierType extends AbstractType
{

    const DEFAULT_MIME_TYPES = [
        'text/plain'
        , 'application/octet-stream'
        , 'application/pdf'
        , 'application/vnd.ms-excel'
        , 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        , 'application/msword'
        , 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['doc_options']['mime_types'])) {
            $options['doc_options'] = array_merge($options['doc_options'], ['mime_types' => self::DEFAULT_MIME_TYPES]);
        }
       
        $mimeTypes = $options['doc_options']['mime_types'];
        $builder->add('title', null, ['label' => 'Libellé', 'required' => false, 'empty_data' => '']);
        $builder->add('file', FileType::class, [
            'label' => false
            //,'data_class' => Fichier::class
            , 'required' => $options['required']
            , 'attr' => ['accept' => implode(',', $mimeTypes)]

            , 'constraints' => [

                new File(
                    ['mimeTypes' => $mimeTypes]
                ),
            ],
        ]);

        $options['doc_options'] = array_except( $options['doc_options'], ['mime_types']);
        

        if (isset($options['doc_options']['upload_dir'])) {

            //dump($options['doc_options']);exit;

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
                $data      = $event->getData();
                $form      = $event->getForm();
                $nonMapped = ['upload_dir'];

                if ($data['file']) {
                    foreach ($options['doc_options'] as $option => $value) {
                       
                        $form->add($option, TextType::class, ['mapped' => !in_array($option, $nonMapped)]);

                        $data[$option] = is_object($value) ? $value->getId() : $value;
                    }

                    $form->add('path', TextType::class);
                    $form->add('folder', TextType::class);
                    $data['folder'] = $options['doc_options']['folder'] ?? '../web/uploads/';
                    $parts = explode($options['doc_options']['folder'], $options['doc_options']['upload_dir']);
                   


                    $data['path'] = $parts[1] ?? '/';

                }

                $event->setData($data);

            });
        }
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Fichier::class
            , 'doc_options' => []
            , 'mime_types' => self::DEFAULT_MIME_TYPES
            , 'required' => false
            //, 'folder' => '/../web/uploads/'
            
          
        ]);

        $resolver->setRequired('doc_options');
        $resolver->setRequired('mime_types');
        $resolver->setRequired('required');
        //$resolver->setRequired('folder');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ps_parametrebundle_fichier';
    }

}
