<?php

namespace App\Tests\EntityListener;

use App\EntityListener\UserListener;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListenerTest extends TestCase
{
    private $userMock;
    private $hasherMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un mock pour User
        $this->userMock = $this->createMock(User::class);

        // Créer un mock pour UserPasswordHasherInterface
        $this->hasherMock = $this->createMock(UserPasswordHasherInterface::class);
    }

    public function testConstruct()
    {
        // Instancier le UserListener avec les mocks
        $listener = new UserListener($this->hasherMock);

        // Vérifier si la construction ne provoque pas d'erreur
        $this->assertInstanceOf(UserListener::class, $listener);
    }

    public function testEncodePassword()
    {
        // Définir un mot de passe en clair pour le mock de l'utilisateur
        $this->userMock->setPlainPassword('password');

        // Configure le mock pour s'attendre à être appelé avec le mot de passe en clair
        $this->hasherMock->expects($this->once())
            ->method('hashPassword')
            ->with(
                $this->identicalTo($this->userMock), // Vérifie que l'argument est identique à $userMock
                'password' // Vérifie que le mot de passe est correct
            )
            ->willReturn('hashed_password');

        // Instancier UserListener avec le mock du hasher
        $listener = new UserListener($this->hasherMock);

        // Appeler la méthode encodePassword avec le mock de l'utilisateur
        $listener->encodePassword($this->userMock);

        // Vérifier si la méthode a correctement appelé le hasher et modifié le mot de passe
        $this->assertEquals('hashed_password', $this->userMock->getPassword());
        $this->assertNull($this->userMock->getPlainPassword());
    }
}

