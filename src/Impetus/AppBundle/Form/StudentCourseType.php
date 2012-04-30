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

class StudentCourseType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('course', 'entity', array('class' => 'ImpetusAppBundle:Course',
                                            'query_builder' => function ($repository) { return $repository->createQueryBuilder('c')->orderBy('c.name', 'ASC'); },
                                            )
                  )
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Impetus\AppBundle\Entity\StudentCourse');
    }

    public function getName()
    {
        return 'impetus_appbundle_studentcoursetype';
    }
}
