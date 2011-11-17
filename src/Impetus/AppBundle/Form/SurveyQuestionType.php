<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SurveyQuestionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('text', 'text',
                  array('label' => 'Question'))
            ->add('type', 'hidden')
            ->add('answers', 'collection',
                  array('type' => new SurveyAnswerType(),
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
        return array('data_class' => 'Impetus\AppBundle\Entity\SurveyQuestion');
    }

    public function getName()
    {
        return 'impetus_appbundle_surveyquestiontype';
    }
}
