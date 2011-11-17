<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuizAnswerType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('label', 'text',
                  array('label' => 'Text Label',
                        'required' => false))
            ->add('value', 'text',
                  array('label' => 'Answer'))
            ->add('correctAnswer', 'checkbox',
                  array('label' => 'Correct?',
                        'required' => false))
        ;
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Impetus\AppBundle\Entity\QuizAnswer');
    }

    public function getName()
    {
        return 'impetus_appbundle_quizanswertype';
    }
}
