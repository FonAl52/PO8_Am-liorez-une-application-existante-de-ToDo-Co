<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Collections\Collection;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testId(): void
    {
        $this->assertNull($this->user->getId());
    }

    public function testUsername(): void
    {
        $username = 'testuser';
        $this->user->setUsername($username);
        $this->assertEquals($username, $this->user->getUsername());
    }

    public function testEmail(): void
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
    }

    public function testCreatedAt(): void
    {
        $dateTime = new \DateTime();
        $this->user->setCreatedAt($dateTime);
        $this->assertSame($dateTime, $this->user->getCreatedAt());
    }

    public function testPassword(): void
    {
        $password = 'password';
        $this->user->setPassword($password);
        $this->assertEquals($password, $this->user->getPassword());
    }

    public function testRoles(): void
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        $this->user->setRoles($roles);
        $this->assertEquals($roles, $this->user->getRoles());
    }

    public function testGetUserIdentifier(): void
    {
        $email = 'test@example.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getUserIdentifier());
    }

    public function testGetPlainPassword(): void
    {
        $plainPassword = 'plain_password';
        $this->user->setPlainPassword($plainPassword);
        $this->assertEquals($plainPassword, $this->user->getPlainPassword());
    }

    public function testSetPlainPassword(): void
    {
        $plainPassword = 'plain_password';
        $this->user->setPlainPassword($plainPassword);
        $this->assertEquals($plainPassword, $this->user->getPlainPassword());
    }

    public function testGetTask(): void
    {
        $this->assertInstanceOf(Collection::class, $this->user->getTask());
    }

    public function testAddTask(): void
    {
        $task = new Task();
        $this->user->addTask($task);
        $this->assertTrue($this->user->getTask()->contains($task));
    }

    public function testRemoveTask(): void
    {
        $task = new Task();
        $this->user->addTask($task);
        $this->user->removeTask($task);
        $this->assertFalse($this->user->getTask()->contains($task));
    }

    public function testEraseCredentials(): void
    {
        $plainPassword = 'plain_password';
        $this->user->setPlainPassword($plainPassword);
        $this->user->eraseCredentials();
        $this->assertNull($this->user->getPlainPassword());
    }
}
