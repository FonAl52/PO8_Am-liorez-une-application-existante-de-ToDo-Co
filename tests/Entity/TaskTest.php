<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

class TaskTest extends TestCase
{
    private Task $task;

    protected function setUp(): void
    {
        $this->task = new Task();
    }

    public function testId(): void
    {
        $this->assertNull($this->task->getId());
    }

    public function testCreatedAt(): void
    {
        $dateTime = new \DateTimeImmutable();
        $this->task->setCreatedAt($dateTime);
        $this->assertSame($dateTime, $this->task->getCreatedAt());
    }

    public function testTitle()
    {
        $title = 'Test Task';
        $this->task->setTitle($title);
        $this->assertEquals($title, $this->task->getTitle());
    }

    public function testContent()
    {
        $content = 'Test task content';
        $this->task->setContent($content);
        $this->assertEquals($content, $this->task->getContent());
    }

    public function testIsDone()
    {
        $this->task->toggle(true);
        $this->assertTrue($this->task->isDone());
    }

    public function testUser()
    {
        $user = new User();
        $this->task->setUser($user);
        $this->assertEquals($user, $this->task->getUser());
    }

}
