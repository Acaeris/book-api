<?php

namespace spec\App\Validator;

use App\Validator\Constraint\ContainsISBN;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ContainsISBNValidatorSpec extends ObjectBehavior
{
    public function let(
        ExecutionContextInterface $context
    ) {
        $this->initialize($context);
    }

    public function it_should_extend_symfony_constraint_validator()
    {
        $this->shouldBeAnInstanceOf(ConstraintValidator::class);
    }

    public function it_should_accept_valid_isbn(
        ContainsISBN $constraint
    ) {
        $this->validate("978-1-491-91866-1", $constraint)->shouldReturn(null);
        $this->validate("9781491918661", $constraint)->shouldReturn(null);
        $this->validate("978-1491918661", $constraint)->shouldReturn(null);
    }

    public function it_should_ignore_empty_value_so_other_constraint_handles_it(
        ContainsISBN $constraint
    ) {
        $this->validate("", $constraint)->shouldReturn(null);
    }

    public function it_should_expect_a_string(
        ContainsISBN $constraint
    ) {
        $this->shouldThrow(UnexpectedValueException::class)->during('validate', [[1234], $constraint]);
    }

    public function it_should_error_on_invalid_format_isbn(
        ContainsISBN $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $builder
    ) {
        $context->buildViolation($constraint->message)->willReturn($builder);
        $builder->setParameter('{{ string }}', "978=1491918661")->willReturn($builder);
        $builder->addViolation()->shouldBeCalled();
        $this->validate("978=1491918661", $constraint);
    }

    public function it_should_error_on_invalid_checksum(
        ContainsISBN $constraint,
        ExecutionContextInterface $context,
        ConstraintViolationBuilderInterface $builder
    ) {
        $context->buildViolation($constraint->message)->willReturn($builder);
        $builder->setParameter('{{ string }}', "9781491918666")->willReturn($builder);
        $builder->addViolation()->shouldBeCalled();
        $this->validate("9781491918666", $constraint);
    }
}