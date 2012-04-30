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

class StudentExamType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('exam', 'entity', array('class' => 'ImpetusAppBundle:Exam',
                                          'query_builder' => function ($repository) { return $repository->createQueryBuilder('e')->orderBy('e.name', 'ASC'); },
                                          )
                  )
            ->add('score', 'integer')
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Impetus\AppBundle\Entity\StudentExam');
    }

    public function getName()
    {
        return 'impetus_appbundle_studentexamtype';
    }
}
