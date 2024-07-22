<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture

    { public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    } 

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);

        $manager->flush();
    }

    private function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$email, $password, $roles]) {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setRoles($roles);

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array<array{string, string, array<string>}>
     */
    private function getUserData(): array
    {
        return [
            // $userData = [$email, $password, $roles];
            ['jane_admin@symfony.com', 'kitten', [User::ROLE_ADMIN]],
            ['tom_admin@symfony.com', 'kitten', [User::ROLE_ADMIN]],
            ['john_user@symfony.com', 'kitten', [User::ROLE_USER]],
        ];
    }
}
