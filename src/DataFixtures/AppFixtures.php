<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public const TEST_USER_EMAIL    = 'test@test.com';
    public const TEST_USER_PASSWORD = 'test';

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->createTestUser();

        $manager->persist($user);
        $manager->flush();
    }

    public function createTestUser(): User
    {
        $user = new User();
        $user
            ->setEmail(self::TEST_USER_EMAIL)
            ->setPassword($this->userPasswordHasher->hashPassword($user, self::TEST_USER_PASSWORD))
            ->setName('Test')
        ;

        return $user;
    }
}
