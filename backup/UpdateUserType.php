<?php

namespace Impetus\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;


class UpdateUserType extends CoreUserType {
    public function buildForm(FormBuilder $builder, array $options) {
        $builder->add('username', null, array('read_only' => true));
        $this->coreFields($builder);
    }

    public function getName() {
        return 'adduser';
    }
}