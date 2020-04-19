<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class ContainsISBN extends Constraint
{
    public $message = 'Invalid ISBN';
}
