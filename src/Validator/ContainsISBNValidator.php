<?php

namespace App\Validator;

use App\Validator\Constraint\ContainsISBN;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsISBNValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ContainsISBN) {
            throw new UnexpectedTypeException($constraint, ContainsISBN::class);
        }

        if (null == $value || '' == $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // Format check
        $regex = '/^(?:ISBN(?:-1[03])?:?\ )?(?=[0-9X]{10}$|(?=(?:[0-9]+[-\ ]){3})[-\ 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[-\ ]?){4})[-\ 0-9]{14,17}$)(?:97[89][-\ ]?)?[0-9]{1,5}[-\ ]?[0-9]+[-\ ]?[0-9]+[-\ ]?[0-9X]$/';

        if (!preg_match($regex, $value, $matches)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
        }

        // Checksum check
        $cleanedValue = preg_replace("/^ISBN|^isbn|-|\ /", "", $value);
        $length = strlen($cleanedValue);

        if ($length === 13) {
            $check = 0;

            for ($i = 0; $i < 13; $i += 2) {
                $check += substr($cleanedValue, $i, 1);
            }

            for ($i = 1; $i < 12; $i += 2) {
                $check += 3 * \substr($cleanedValue, $i, 1);
            }

            if ($check % 10 === 0) {
                return;
            }
        } elseif ($length === 10) {
            $check = 0;

            for ($i = 0; $i < 10; $i++) {
                if (strtoupper($cleanedValue[$i]) === 'X') {
                    $check += 10 * intval(10 - $i);
                } else {
                    $check += intval($cleanedValue[$i]) * intval(10 - $i);
                }
            }

            if ($check % 11 === 0) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
    }
}
