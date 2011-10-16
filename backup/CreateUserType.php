<?php

namespace Impetus\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class CreateUserType extends CoreUserType {
    public function buildForm(FormBuilder $builder, array $options) {
        $builder->add('username');
        $this->coreFields($builder);
    }

    public function getName() {
        return 'adduser';
    }
}