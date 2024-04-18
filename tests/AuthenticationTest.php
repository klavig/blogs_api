<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AuthenticationTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $client    = self::createClient();
        $container = self::getContainer();

        /** @var AppFixtures $fixtures */
        $fixtures       = $container->get(AppFixtures::class);
        $em             = $container->get(EntityManagerInterface::class);
        $userRepository = $em->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => AppFixtures::TEST_USER_EMAIL]);
        if (null === $user) {
            $user = $fixtures->createTestUser();

            $em->persist($user);
            $em->flush();
        }

        // retrieve a token
        $response = $client->request('POST', '/api/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email'    => AppFixtures::TEST_USER_EMAIL,
                'password' => AppFixtures::TEST_USER_PASSWORD,
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/api/blog_posts');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/blog_posts', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}