<?php

/*
 * This file is part of the ImpetusAppBundle
 *
 * (c) Ryan Lewis <lewis.ryan.j@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Impetus\AppBundle\Component\Validator\Constraint;

use Symfony\Component\Validator\Constraint;


/**
* @Annotation
*/
class EqualsField extends Constraint
{
    public $message = 'This value does not equal the {{ value }} field';
    public $value;

    /**
     * {@inheritDoc}
     */
    public function getDefaultOption()
    {
        return 'value';
    }

    /**
     * {@inheritDoc}
     */
    public function getRequiredOptions()
    {
        return array('value');
    }
}