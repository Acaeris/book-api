<?php

namespace spec\App\Validator\Constraint;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\Constraint;

class ContainsISBNSpec extends ObjectBehavior
{
    public function it_should_extend_symfony_constraint_class()
    {
        $this->shouldBeAnInstanceOf(Constraint::class);
    }
}