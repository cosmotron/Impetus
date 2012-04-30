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


class MessageType extends AbstractType {
    public function buildForm(FormBuilder $builder, array $options) {
        $builder->add('subject', 'text');
        $builder->add('content', 'textarea');
    }

    public function getDefaultOptions(array $options) {
        return array('data_class' => 'Impetus\AppBundle\Entity\Message');
    }

    public function getName() {
        return 'message';
    }
}