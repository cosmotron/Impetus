<?php

namespace Impetus\AppBundle\Component\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class EqualsFieldValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constrain for the validation
     *
     * @return Boolean Whether or not the value is valid
     */
    public function isValid($value, Constraint $constraint)
    {
        if ($value !== $this->context->getRoot()->get($constraint->value)->getData()) {
            $this->setMessage($constraint->message, array('{{ value }}' => $constraint->value,));

            return false;
        }

        return true;
    }
}