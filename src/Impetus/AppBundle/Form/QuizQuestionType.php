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

class QuizQuestionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('text', 'textarea',
                  array('label' => 'Question')
                  )
            ->add('answers', 'collection',
                  array('type' => new QuizAnswerType(),
                        'allow_add'      => true,
                        'allow_delete'   => true,
                        'prototype'      => true,
                        'prototype_name' => 'answers',
                        'by_reference'   => false,
                        'required'       => false,
                        )
                  )
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Impetus\AppBundle\Entity\QuizQuestion');
    }

    public function getName()
    {
        return 'impetus_appbundle_quizquestiontype';
    }
}
