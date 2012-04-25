<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class EventType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('eventDate', 'date', array('widget' => 'choice',
                                        'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                                        'label' => 'Event Occurs On',))
        ;
    }

    public function getName()
    {
        return 'impetus_appbundle_eventtype';
    }
}
