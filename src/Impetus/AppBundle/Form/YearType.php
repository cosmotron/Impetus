<?php

namespace Impetus\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class YearType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('archived')
            ->add('year')
        ;
    }

    public function getName()
    {
        return 'impetus_appbundle_yeartype';
    }
}
