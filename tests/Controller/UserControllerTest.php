<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    private $manager;

    
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->manager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
    }
    
    private function cleanDB(): void
    {
        $this->manager->getConnection()->executeStatement('DELETE FROM user WHERE id > 3');
    }

    public function testListActionWithoutLogin()
    {
        // If the user isn't logged, should redirect to the home page
        $crawler = $this->client->request('GET', '/users');
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        // Test if error message is present
        static::assertSame("Error! Vous n'avez pas les droits nécessaires pour accéder à cette page.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function loginAsUser(): void
    {
        $crawler = $this->client->request('GET', '/login');

        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Connexion')->form();
        $form['_username'] = 'user@example.org';
        $form['_password'] = 'test';
        $this->client->submit($form);

        $this->client->followRedirect();
    }

    public function loginAsAdmin(): void
    {
        $crawler = $this->client->request('GET', '/login');

        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Connexion')->form();
        $form['_username'] = 'admin@example.org';
        $form['_password'] = 'test';
        $this->client->submit($form);

        $this->client->followRedirect();
    }

    public function testListActionAsUser()
    {
        $this->loginAsUser();

        // If the user is log as USER, should redirect to the home page
        $crawler = $this->client->request('GET', '/users');
        static::assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        // Test if error message is present
        static::assertSame("Error! Vous n'avez pas les droits nécessaires pour accéder à cette page.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testListActionAsAdmin()
    {
        $this->loginAsAdmin();

        // If the user is log as ADMIN, should get access to the users list
        $crawler = $this->client->request('GET', '/users');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if error message is present
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());
    }

    public function testCreateActionAsUser()
    {
        $this->loginAsUser();
        
         // If the user is log as USER, should redirect to the home page
         $crawler = $this->client->request('GET', '/users/create');
         static::assertSame(302, $this->client->getResponse()->getStatusCode());
 
         $crawler = $this->client->followRedirect();
         // Test if error message is present
         static::assertSame("Error! Vous n'avez pas les droits nécessaires pour accéder à cette page.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testCreateActionAsAdmin()
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', '/users/create');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][first]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][second]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
        static::assertSame(2, $crawler->filter('input[name="user[roles][]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = 'newuser';
        $form['user[plainPassword][first]'] = 'test';
        $form['user[plainPassword][second]'] = 'test';
        $form['user[email]'] = 'newUser@example.org';
        $form['user[roles][0]']->tick();
        $this->client->submit($form);

        static::assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Success! L'utilisateur a bien été ajouté.", $crawler->filter('div.alert.alert-success')->text());

        $this->cleanDB();
    }

    public function testEditActionAsUser()
    {
        $this->loginAsUser();
        
         // If the user is log as USER, should redirect to the home page
         $crawler = $this->client->request('GET', '/users/3/edit');
         static::assertSame(302, $this->client->getResponse()->getStatusCode());
 
         $crawler = $this->client->followRedirect();
         // Test if error message is present
         static::assertSame("Error! Vous n'avez pas les permissions nécessaires pour accéder à cette page.", $crawler->filter('div.alert.alert-danger')->text());
    }
    
    public function testEditAction()
    {
        $this->loginAsAdmin();

        $crawler = $this->client->request('GET', '/users/3/edit');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="user[username]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][first]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[plainPassword][second]"]')->count());
        static::assertSame(1, $crawler->filter('input[name="user[email]"]')->count());
        static::assertSame(2, $crawler->filter('input[name="user[roles][]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'editedUser';
        $form['user[plainPassword][first]'] = 'test';
        $form['user[plainPassword][second]'] = 'test';
        $form['user[email]'] = 'editedUser@example.org';
        $form['user[roles][0]']->tick();
        $this->client->submit($form);

        static::assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->request('GET', '/users/3/edit');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        //Reset user information so that the tests continue to work
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'user';
        $form['user[plainPassword][first]'] = 'test';
        $form['user[plainPassword][second]'] = 'test';
        $form['user[email]'] = 'user@example.org';
        $form['user[roles][0]']->tick();
        $this->client->submit($form);

        $this->cleanDB();
    }
}