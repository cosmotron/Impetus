<?php

namespace Impetus\AppBundle\Form;

use Impetus\AppBundle\Form\ActivityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        /*
        $builder
            ->add('activities', 'entity', array('empty_value' => 'Choose an activity',
                                                'class' => 'ImpetusAppBundle:Activity',
                                                'label' => 'Activities',
                                                'property' => 'name',
                                                'multiple' => true,
                                                )
                  )
            ;
        */

        $builder
            ->add('activities', 'collection',
                  array('type' => new StudentActivityType(),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                        'required' => false,
                        )
                  )
        ;

    }
    /*
    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Impetus\AppBundle\Entity\Student');
    }
    */
    public function getName()
    {
        return 'impetus_appbundle_studenttype';
    }
}
