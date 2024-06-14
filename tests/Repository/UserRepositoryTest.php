<?php

namespace App\Tests\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private $userRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    public function testUpgradePasswordSuccess()
    {
        // Create a new user with mandatory fields
        $user = new User();
        $user->setUsername('testuser');
        $user->setPassword('old_password');
        $user->setEmail('testuser@example.com');

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Upgrade the password
        $newHashedPassword = 'new_hashed_password';
        $this->userRepository->upgradePassword($user, $newHashedPassword);

        // Check that the password has been updated
        $this->assertSame($newHashedPassword, $user->getPassword());
    }

    public function testUpgradePasswordUnsupportedUserException()
    {
        $this->expectException(UnsupportedUserException::class);

        // Create a mock of PasswordAuthenticatedUserInterface
        $nonUser = $this->createMock(PasswordAuthenticatedUserInterface::class);
        $newHashedPassword = 'new_hashed_password';

        // Check that the exception is thrown
        $this->userRepository->upgradePassword($nonUser, $newHashedPassword);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
