<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    private $security;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un mock de Security
        $this->security = $this->createMock(Security::class);
    }

    public function testSubmitValidData()
    {
        $formData = [
            'username' => 'testuser',
            'plainPassword' => [
                'first' => 'password',
                'second' => 'password',
            ],
            'email' => 'test@example.com',
            'roles' => ['ROLE_ADMIN'],
        ];
        $objectToCompare = new User();

        $form = $this->factory->create(UserType::class, $objectToCompare);

        $form->submit($formData);

        $expected = new User();
        $expected->setUsername('testuser');
        $expected->setPlainPassword('password');
        $expected->setEmail('test@example.com');
        $expected->setRoles(['ROLE_ADMIN']);


        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());


        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
