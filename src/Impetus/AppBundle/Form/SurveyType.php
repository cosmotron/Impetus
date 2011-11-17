<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('questions', 'collection',
                  array('type' => new SurveyQuestionType(),
                        'allow_add'      => true,
                        'allow_delete'   => true,
                        'prototype'      => true,
                        'prototype_name' => 'questions',
                        'by_reference'   => false,
                        'required'       => false,
                        )
                  )
        ;
    }

    public function getName()
    {
        return 'impetus_appbundle_surveytype';
    }
}
