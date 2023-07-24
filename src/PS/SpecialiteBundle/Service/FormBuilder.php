<?php

namespace PS\SpecialiteBundle\Service;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;

class FormBuilder
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var array
     */
    private $builder = [];

    /**
     * @var mixed
     */
    private $tmpBuilder = null;

    /**
     * @var array
     */
    private $values = [];

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var mixed
     */
    private $formFactory;

    /**
     * @var string
     */
    private $typeNameSpace = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\';

    /**
     * @var array
     */
    private $typesMap = [
        'VoidType'     => 'TextType',
        'RadioType'    => 'ChoiceType',
        'DateTimeType' => 'collot_datetime',
    ];

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param $data
     */
    public function load($data, $values)
    {
        $this->data   = $data;
        $this->values = $values;
    }

    /**
     * @param $aliasType
     * @return mixed
     */
    public function getType($aliasType)
    {
        if (isset($this->typesMap[$aliasType])) {
            $type = $this->typesMap[$aliasType];
        } else {
            $type = $aliasType;
        }

        return $this->typeNameSpace . $type;
    }

    /**
     * @param PropertyInterface $property
     */
    public function addProperty(PropertyInterface $property)
    {
        $this->properties[] = $property;
    }

    /**
     * @param $type
     */
    public function getProperty($type)
    {

    }

    /**
     * @param $enfants
     */
    public function getFormChild($enfants)
    {

    }

    /**
     * @param $champ
     */
    public function generatePrototype($champ)
    {

    }

    /**
     * @param $child
     */
    public function addChild($champ, &$tmpBuilder = null)
    {

        if (is_null($tmpBuilder)) {
            $tmpBuilder = $this->formFactory->createBuilder();
        }
        

        $aliasType  = $champ->getTypeChamp()->getAliasTypeChamp();
        $aliasChamp = $champ->getAliasChamp();
        //echo $champ->getId() == 25;

        //$properties = [];
        $label      = $champ->getLibChamp();
        $ignoreType = false;
        if ($label == '_') {
            $label = false;
        }

        $valeurs    = $champ->getValeurAutoriseeChamp();
        $properties = ['label' => $label, 'attr' => []];

        if ($aliasType == 'VoidType') {
            $aliasType = 'TextType';

            //echo 'FOO';

            $properties = [
                'attr'     => ['class' => 'hide is-hidden remove-input'],
                'required' => false,
                'label'    => $label,
            ];

        } elseif ($aliasType == 'RadioType') {

            $aliasType  = 'ChoiceType';
            $properties = [
                'choices'  => array_combine($valeurs, $valeurs),
                'expanded' => true,
                'multiple' => false,

                'placeholder' => null,
                'label'    => $label,
            ];
        } elseif ($aliasType == 'ChoiceType') {

            $properties = [
                'choices'  => array_combine($valeurs, $valeurs),
                'expanded' => true,
                'multiple' => true,
                'label'    => $label,
            ];

            //$choices = $champ->getValeurAutoriseeChamp();

        } elseif ($aliasType == 'BoolType') {
            $aliasType  = 'ChoiceType';
            $choices    = array_combine(['oui', 'non'], ['oui', 'non']);
            $properties = [
                'choices'  => $choices,
                'expanded' => true,
                'multiple' => false,
                'placeholder' => false,
                'label'    => $label,
                'data'     => isset($this->values[$aliasChamp]) ? $this->values[$aliasChamp] : 'non',
            ];
            //dump($properties);exit;
        } elseif ($aliasType == 'DateType') {
            $properties = [
                'attr'   => ['class' => 'datepicker'],
                'widget' => 'single_text'
                , 'format' => 'dd/MM/yyyy'
                , 'label' => $label,
            ];
        } elseif ($aliasType == 'DateTimeType') {
            $aliasType  = 'collot_datetime';
            $ignoreType = true;
            $properties = [
                'label' => $label
                , 'widget' => 'single_text'
                , 'attr' => ['autocomplete' => 'off']
                , 'pickerOptions' => [
                    'format'             => 'dd/mm/yyyy hh:ii',
                    'weekStart'          => 1,
                    //'startDate' => date('Y-m-d'), //example
                    //'endDate' => '01/01/3000', //example
                    'autoclose'          => true,
                    'startView'          => 'month',
                    'minView'            => 'hour',
                    'maxView'            => 'decade',
                    'todayBtn'           => true,
                    'todayHighlight'     => true,
                    'keyboardNavigation' => true,
                    'language'           => 'fr',
                    'forceParse'         => false,
                    'minuteStep'         => 5,
                    'pickerPosition'     => 'bottom-right',
                    'viewSelect'         => 'hour',
                    'showMeridian'       => false,
                ],
            ];
        }

        if (!isset($properties['attr'])) {
            //$properties['attr'] = [];
        }

        //dump($champ->getLibChamp());

        $tmpBuilder->add($champ->getAliasChamp(), $this->getNamespacedType($aliasType), $properties);

        //return $this;
    }

    /**
     * @param $data
     * @param null $_formBuilder
     */
    public function getForm($data = null, &$_formBuilder = null, $parent = null)
    {

        $data = $data ? $data : $this->data;

        //$tmpBuilder = $this->formFactory->createBuilder();


        $formBuilder = $_formBuilder ? $_formBuilder :
        $this->formFactory->createBuilder(
            FormType::class
            , []
            , ['allow_extra_fields' => true]
        );

        

        foreach ($data as $champ) {

            $depth = 0;

            $aliasType  = $champ->getTypeChamp()->getAliasTypeChamp();
            $aliasChamp = $champ->getAliasChamp();

            //echo $champ->getId() == 25;

            //$properties = [];
            $label      = $champ->getLibChamp();
            $ignoreType = false;
            if ($label == '_') {
                $label = false;
            }

            $valeurs    = $champ->getValeurAutoriseeChamp();
            $properties = ['label' => $label, 'attr' => []];

            if ($aliasType == 'VoidType') {
                $aliasType = 'TextType';

                //echo 'FOO';

                $properties = [
                    'attr'     => ['class' => 'hide is-hidden remove-input'],
                    'required' => false,
                    'label'    => $label,
                ];

            } elseif ($aliasType == 'RadioType') {

                $aliasType  = 'ChoiceType';
                $properties = [
                    'choices'  => array_combine($valeurs, $valeurs),
                    'expanded' => true,
                    'multiple' => false,
                    'placeholder' => false,
                    'label'    => $label,
                ];
            } elseif ($aliasType == 'ChoiceType') {

                $properties = [
                    'choices'  => array_combine($valeurs, $valeurs),
                    'expanded' => true,
                    'multiple' => true,
                    'label'    => $label,
                ];

                //$choices = $champ->getValeurAutoriseeChamp();

            } elseif ($aliasType == 'BoolType') {
                $aliasType  = 'ChoiceType';
                $choices    = array_combine(['oui', 'non'], ['oui', 'non']);
                $properties = [
                    'choices'  => $choices,
                    'expanded' => true,
                    'multiple' => false,
                    'placeholder' => false,
                    'label'    => $label,
                    //'data'     => isset($this->values[$aliasChamp]) ? $this->values[$aliasChamp] : 'non',
                ];
                //dump($properties);exit;
            } elseif ($aliasType == 'DateType') {
                $properties = [
                    'attr'   => ['class' => 'datepicker'],
                    'widget' => 'single_text'
                    , 'format' => 'dd/MM/yyyy'
                    , 'label' => $label,
                ];
            } elseif ($aliasType == 'DateTimeType') {
                $aliasType  = 'collot_datetime';
                $ignoreType = true;
                $properties = [
                    'label' => $label
                    , 'widget' => 'single_text'
                    , 'attr' => ['autocomplete' => 'off']
                    , 'pickerOptions' => [
                        'format'             => 'dd/mm/yyyy hh:ii',
                        'weekStart'          => 1,
                        //'startDate' => date('Y-m-d'), //example
                        //'endDate' => '01/01/3000', //example
                        'autoclose'          => true,
                        'startView'          => 'month',
                        'minView'            => 'hour',
                        'maxView'            => 'decade',
                        'todayBtn'           => true,
                        'todayHighlight'     => true,
                        'keyboardNavigation' => true,
                        'language'           => 'fr',
                        'forceParse'         => false,
                        'minuteStep'         => 5,
                        'pickerPosition'     => 'bottom-right',
                        'viewSelect'         => 'hour',
                        'showMeridian'       => false,
                    ],
                ];
            }

            if (!isset($properties['attr'])) {
                $properties['attr'] = [];
            }

            if ($parent) {

                //dump($parent);
                $parentName  = $parent->getAliasChamp();
                $parentValue = $champ->getValeurChampParent();
                $allParents = $champ->getAllParents();

                

                $properties['attr'] = array_merge($properties['attr'], [
                    'data-parent' => implode(' ', array_keys($allParents)),
                    'data-value'  => $parentValue,
                ]);

               

                if (isset($properties['attr']['class'])) {
                    $oldClass = $properties['attr']['class'];
                } else {
                    $oldClass = '';
                }

                $_count = 0;

                foreach ($allParents as $fieldName => $fieldType) {
                    if ($fieldType == 'VoidType') {
                        $_count += 1;
                    }
                }

                $newClass = $oldClass.' has-parent parent-'.$parentName;

                if ($_count != count($allParents)) {
                    $newClass .= ' disabled';
                    $properties['attr']['disabled'] = true;
                }
                $properties['attr']['class'] = $newClass;
                //Conditionels
                $properties['required'] = false;


                
            }


            $properties['attr']['data-validation'] = $champ->getValidationGroup();

            $type = $this->getNamespacedType($aliasType);

            //$formBuilder->add($champ->getAliasChamp(), $type, $properties);

            $enfants = $champ->getChampEnfants()->toArray();


            //$tmpBuilder = $this->formFactory->createBuilder();
            if ($count = $champ->getChampEnfants()->count()) {

                

                //$tmpBuilder = null;

                foreach ($enfants as $enfant) {
                    $children[] = $enfant->getAliasChamp();
                }

                if (!isset($properties['attr']['data-children'])) {
                    //dump($children);exit;

                    $properties['attr'] = array_merge($properties['attr'], [
                        'data-children' => implode(' ', array_slice($children, -$count)),
                        //'data-prototype' => $this->generatePrototype($champ)
                    ]);
                }

                $formBuilder->add($champ->getAliasChamp(), $type, $properties);


                //dump($champ->getAllParents());exit;


                
                //$formBuilder = null;

                //unset($properties);

                //$tmpBuilder = $this->formFactory->createBuilder();

                //dump($champ->getLibChamp(), $enfants);exit;

                /*if ($key == $count - 1) {
                $formBuilder->add($this->tmpBuilder);
                } else {
                $this->tmpBuilder = null;
                }*/

                //$resr->add($tmpBuilder);

                //$formBuilder = $tmpBuilder0;

                //$tmp = null;

                //$tmpBuilder = null;

                //$this->getFormChild($formBuilder, $champ);
                //$this->getForm($champ)
                $this->getForm($enfants, $formBuilder, $champ);
            } else {
                 $formBuilder->add($champ->getAliasChamp(), $type, $properties);
            
            }


           
            foreach ($enfants as $enfant) {
                //dump($enfant->getLibChamp());
                //$tmpBuilder->add($enfant->getAliasChamp());
            }

        

        }

        //exit;

        //dump($this->tmpBuilder);exit;

        return $formBuilder;
    }

    /**
     * @param $type
     * @param $ignoreNamespace
     */
    public function getNamespacedType($type)
    {
        if (isset($this->typesMap[$type])) {
            $type = $this->typesMap[$type];
        }
        return strpos($type, 'Type') !== false ? $this->typeNameSpace . $type : $type;
    }

    /**
     * @param $type
     */
    public function getPropertiesByType($type, $champ, $merge = [])
    {
        return $this->mergeProperty($this->{'get' . basename($type) . 'Properties'}($champ), $merge);
    }

    /**
     * @param $properties
     * @param $merge
     * @return mixed
     */
    public function mergeProperty($properties, $merge)
    {
        return $properties;
    }

    /**
     * @param $champ
     */
    public function getRadioTypeProperties($champ)
    {

    }

    /**
     * @param $champ
     */
    public function getDateTypeProperties($champ)
    {

    }

    /**
     * @param $champ
     */
    public function getDateTimeTypeProperties($champ)
    {
        return [
            'label' => $champ->getLibChamp()
            , 'widget' => 'single_text'
            , 'attr' => ['autocomplete' => 'off']
            , 'pickerOptions' => [
                'format'             => 'dd/mm/yyyy hh:ii',
                'weekStart'          => 1,
                'autoclose'          => true,
                'startView'          => 'month',
                'minView'            => 'hour',
                'maxView'            => 'decade',
                'todayBtn'           => true,
                'todayHighlight'     => true,
                'keyboardNavigation' => true,
                'language'           => 'fr',
                'forceParse'         => false,
                'minuteStep'         => 5,
                'pickerPosition'     => 'bottom-right',
                'viewSelect'         => 'hour',
                'showMeridian'       => false,
            ],
        ];
    }

    /**
     * @param $champ
     */
    public function getBoolTypeProperties($champ)
    {
        $choices           = array_combine(['oui', 'non'], ['oui', 'non']);
        return $properties = [
            'choices'  => $choices,
            'expanded' => true,
            'multiple' => false,
            'label'    => $champ->getLibChamp(),
        ];
    }

}
