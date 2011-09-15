<?php

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