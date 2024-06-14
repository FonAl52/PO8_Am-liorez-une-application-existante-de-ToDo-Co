<?php

namespace App\DataFixtures\Dev;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @codeCoverageIgnore
 */
class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function load(ObjectManager $manager)
    {
        // Anonymous User
        $anonymUser = new User();
        $anonymUser->setUsername('anonymous')
            ->setEmail('anonymous@example.org')
            ->setPassword($this->hasher->hashPassword($anonymUser, 'test'))
            ->setRoles(['ROLE_ANONYMOUS']);
        $manager->persist($anonymUser);
        $this->addReference('user-anonymous', $anonymUser);

        // Admin User
        $adminUser = new User();
        $adminUser->setUsername('admin')
            ->setEmail('admin@example.org')
            ->setPassword($this->hasher->hashPassword($adminUser, 'test'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);
        $this->addReference('user-admin', $adminUser);

        // Simple User
        $simpleUser = new User();
        $simpleUser->setUsername('user')
            ->setEmail('user@example.org')
            ->setPassword($this->hasher->hashPassword($simpleUser, 'test'))
            ->setRoles(['ROLE_USER']);
        $manager->persist($simpleUser);
        $this->addReference('user-simple', $simpleUser);

        // Task created by simple user
        $task1 = new Task();
        $task1->setTitle('Tâche 1')
            ->setContent('Tâche d\'exemple créée par un utilisateur simple')
            ->setUser($this->getReference('user-simple'));
        $manager->persist($task1);

        // Task created by admin user
        $task2 = new Task();
        $task2->setTitle('Tâche 2')
            ->setContent('Tâche d\'exemple créée par un admin')
            ->setUser($this->getReference('user-admin'));
        $manager->persist($task2);

        $manager->flush();

        // Task created by anonym user
        $task3 = new Task();
        $task3->setTitle('Tâche 3')
            ->setContent('Tâche d\'exemple créée par un utilisateur anonyme')
            ->setUser($this->getReference('user-anonymous'));
        $manager->persist($task3);

        $manager->flush();
    }
}
