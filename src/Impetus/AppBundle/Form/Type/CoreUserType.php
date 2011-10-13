<?php

namespace Impetus\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class CoreUserType extends AbstractType {
    public function buildForm(FormBuilder $builder, array $options) {
        $this->coreFields($builder);
    }

    public function getDefaultOptions(array $options) {
        return array('data_class' => 'Impetus\AppBundle\Entity\User');
    }

    public function getName() {
        return 'coreuser';
    }

    protected function coreFields(FormBuilder $builder) {
        $builder->add('password', 'password');
        $builder->add('firstName', null, array('label' => 'First Name'));
        $builder->add('lastName', null, array('label' => 'Last Name'));
        $builder->add('email');
        $builder->add('birthday', 'birthday', array('required' => false));
        $builder->add('ethnicity', 'choice', array('choices' => array('american_indian' => 'American Indian',
                                                                      'asian' => 'Asian',
                                                                      'black' => 'Black or African American',
                                                                      'hispanic' => 'Hispanic of any race',
                                                                      'pacific_islander' => 'Pacific Islander',
                                                                      'white' => 'White',
                                                                      'other' => 'Other'),
                                                   'required'  => false,
                                                   ));
        $builder->add('gender', 'choice', array('choices'   => array('male' => 'Male',
                                                                     'female' => 'Female'),
                                                'required'  => false,
                                                ));
        $builder->add('userRoles', 'entity', array('empty_value' => 'Choose a role',
                                                   'class'       => 'ImpetusAppBundle:Role',
                                                   'label'       => 'User Role',
                                                   'multiple'    => true,
                                                  ));
    }
}