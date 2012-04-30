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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StudentActivityType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('activity', 'entity', array('class' => 'ImpetusAppBundle:Activity',
                                              'query_builder' => function ($repository) { return $repository->createQueryBuilder('a')->orderBy('a.name', 'ASC'); },
                                              )
                  )
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Impetus\AppBundle\Entity\StudentActivity');
    }

    public function getName()
    {
        return 'impetus_appbundle_studentactivitytype';
    }
}
