<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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