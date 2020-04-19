<?php

namespace spec\App\Entity;

use App\Entity\Immutable\ImmutableException;
use PhpSpec\ObjectBehavior;

class UserSpec extends ObjectBehavior
{
    private $userId = 1;
    private $email = 'test@example.com';
    private $roles = ['ROLE_USER'];
    private $password = 'SomeHashedPasswordShouldBeHere';
    private $apiToken = "02g30909320923";

    public function let()
    {
        $this->beConstructedWith(
            $this->userId,
            $this->email,
            $this->roles,
            $this->password,
            $this->apiToken
        );
    }

    public function it_should_be_immutable()
    {
        $this->shouldThrow(ImmutableException::class)->during("__set", ["apiToken", "badidea"]);
    }

    public function it_has_a_user_id()
    {
        $this->getId()->shouldReturn($this->userId);
    }

    public function it_has_an_email()
    {
        $this->getEmail()->shouldReturn($this->email);
    }

    public function it_has_a_display_username()
    {
        $this->getUsername()->shouldReturn($this->email);
    }

    public function it_has_user_roles()
    {
        $this->getRoles()->shouldReturn($this->roles);
    }

    public function it_has_a_password()
    {
        $this->getPassword()->shouldReturn($this->password);
    }

    public function it_has_an_api_token()
    {
        $this->getApiToken()->shouldReturn($this->apiToken);
    }
}