<?php

namespace Impetus\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class NewDistrictType extends AbstractType {
    public function buildForm(FormBuilder $builder, array $options) {
        $builder->add('name');
    }

    public function getDefaultOptions(array $options) {
        return array('data_class' => 'Impetus\AppBundle\Entity\District');
    }

    public function getName(){
        return 'newDistrict';
    }
}