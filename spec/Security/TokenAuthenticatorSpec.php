<?php

namespace spec\App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticatorSpec extends ObjectBehavior
{
    private $token = "02g30909320923";

    public function let(
        EntityManagerInterface $em,
        Request $request,
        HeaderBag $header,
        UserRepository $userRepository
    ) {
        $request->headers = $header;
        $em->getRepository(User::class)->willReturn($userRepository);
        $this->beConstructedWith($em);
    }

    public function it_should_check_header_has_auth_token(
        Request $request,
        HeaderBag $header
    ) {
        $header->has('X-AUTH-TOKEN')->willReturn(true);
        $this->supports($request)->shouldReturn(true);

        $header->has('X-AUTH-TOKEN')->willReturn(false);
        $this->supports($request)->shouldReturn(false);
    }

    public function it_should_fetch_auth_token(
        Request $request,
        HeaderBag $header
    ) {
        $header->get('X-AUTH-TOKEN')->willReturn($this->token);
        $this->getCredentials($request)->shouldReturn($this->token);
    }

    public function it_should_fetch_the_user(
        UserProviderInterface $userProvider,
        UserRepository $userRepository,
        User $user
    ) {
        $userRepository->findByApiToken($this->token)->willReturn($user);
        $this->getUser($this->token, $userProvider)->shouldReturn($user);
    }

    public function it_should_check_authentication(
        UserInterface $user
    ) {
        $this->checkCredentials($this->token, $user)->shouldReturn(true);
    }

    public function it_has_no_success_response(
        Request $request,
        TokenInterface $token
    ) {
        $this->onAuthenticationSuccess($request, $token, '')->shouldReturn(null);
    }

    public function it_has_a_failure_response(
        Request $request,
        AuthenticationException $exception
    ) {
        $exception->getMessageKey()->shouldBeCalled();
        $exception->getMessageData()->willReturn([]);
        $this->onAuthenticationFailure($request, $exception)->shouldReturnAnInstanceOf(JsonResponse::class);
    }

    public function it_has_a_response_when_no_auth_supplied(
        Request $request,
        AuthenticationException $exception
    ) {
        $this->start($request, $exception)->shouldReturnAnInstanceOf(JsonResponse::class);
    }

    public function it_should_not_support_remember_me()
    {
        $this->supportsRememberMe()->shouldReturn(false);
    }
}