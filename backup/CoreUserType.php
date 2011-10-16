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
        $builder->add('email');
        //$builder->add('password', 'password');
        //$builder->add('passwordConfirm', 'password');
        $builder->add('firstName', null, array('label' => 'First Name'));
        $builder->add('lastName', null, array('label' => 'Last Name'));
        //$builder->add('graduated');
        //$builder->add('college', 'text', array('required' => false));
        /*
        $builder->add('district', 'entity', array('empty_value' => 'Choose a district',
                                                  'class'       => 'ImpetusAppBundle:District',
                                                  ));
        */
        $builder->add('userRoles', 'entity', array('empty_value' => 'Choose a role',
                                                   'class'       => 'ImpetusAppBundle:Role',
                                                   'label'       => 'User Role',
                                                   'multiple'    => true,
                                                  ));
    }
}