<?php

namespace spec\App\Repository;

use App\Entity\User;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PhpSpec\ObjectBehavior;

class UserRepositorySpec extends ObjectBehavior
{
    private $mockData = [
        [
            "userId" => "1",
            "email" => "test@example.com",
            "roles" => ['user'],
            "password" => "SomeHashedPasswordShouldBeHere",
            "apiToken" => "i83402940f93fn0v293"
        ]
    ];

    public function let(
        ManagerRegistry $manager,
        EntityManagerInterface $em,
        ClassMetadata $metadata,
        QueryBuilder $builder,
        AbstractQuery $query,
        User $user
    ) {
        $manager->getManagerForClass(User::class)->willReturn($em);
        $em->getClassMetadata(User::class)->willReturn($metadata);
        $metadata->name = "users";
        $em->createQueryBuilder()->willReturn($builder);
        $builder->select('u')->willReturn($builder);
        $builder->from('users', 'u', null)->willReturn($builder);
        $builder->getQuery()->willReturn($query);
        $query->getOneOrNullResult()->willReturn($user);
        $this->beConstructedWith($manager);
    }

    public function it_should_be_able_to_fetch_by_user_id(
        QueryBuilder $builder
    ) {
        $builder->andWhere('u.userId = :userId')->willReturn($builder);
        $builder->setParameter('userId', 1)->willReturn($builder);
        $this->findByUser(1)->shouldReturnAnInstanceOf(User::class);
    }

    public function it_should_be_able_to_fetch_by_api_token(
        QueryBuilder $builder
    ) {
        $builder->andWhere('u.apiToken = :apiToken')->willReturn($builder);
        $builder->setParameter('apiToken', "i83402940f93fn0v293")->willReturn($builder);
        $this->findByApiToken("i83402940f93fn0v293")->shouldReturnAnInstanceOf(User::class);
    }
}