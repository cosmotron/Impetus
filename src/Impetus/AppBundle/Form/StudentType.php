<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Form;

use Impetus\AppBundle\Form\ActivityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
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
            ->add('courses', 'collection',
                  array('type' => new StudentCourseType(),
                        'allow_add' => true,
                        'allow_delete' => true,
                        'prototype' => true,
                        'by_reference' => false,
                        'required' => false,
                        )
                  )
            ->add('exams', 'collection',
                  array('type' => new StudentExamType(),
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
