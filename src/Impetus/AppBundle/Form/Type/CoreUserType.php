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
        $builder
            ->add('password', 'password')
            ->add('firstName', null,
                  array('label' => 'First Name',
                        )
                  )
            ->add('lastName', null,
                  array('label' => 'Last Name',
                        )
                  )
            ->add('email')
            ->add('birthday', 'birthday',
                  array('years' => range(date('Y'), (date('Y') - 100)),
                        'required' => false,
                        )
                  )
            ->add('ethnicity', 'choice',
                  array('choices' => array('american_indian' => 'American Indian',
                                           'asian' => 'Asian',
                                           'black' => 'Black or African American',
                                           'hispanic' => 'Hispanic of any race',
                                           'pacific_islander' => 'Pacific Islander',
                                           'white' => 'White',
                                           'other' => 'Other'),
                        'required'  => false,
                        )
                  )
            ->add('gender', 'choice',
                  array('choices' => array('male' => 'Male',
                                           'female' => 'Female'
                                           ),
                        'required'  => false,
                        )
                  )
            ->add('userRoles', 'entity',
                  array('empty_value' => 'Choose a role',
                        'class'       => 'ImpetusAppBundle:Role',
                        'label'       => 'User Role',
                        'multiple'    => true,
                        )
                  )
            ->add('graduated', 'choice',
                  array('choices' => range(date('Y'), (date('Y') - 10)),
                        'required' => false,
                        )
                  )
            ->add('college', 'text',
                  array('required' => false
                        )
                  )
            ->add('major', 'choice',
                  array('choices' => array('cs' => 'Computer Science',
                                           'math' => 'Mathematics',
                                           'physics' => 'Physics',
                                           'other' => 'Other'),
                        'required'  => false,
                        )
                  )
            ;
    }
}