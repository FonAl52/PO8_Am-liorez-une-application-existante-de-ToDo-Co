<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testLogin()
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

        // Check if the response is a redirection (status code 302)
        static::assertTrue($this->client->getResponse()->isRedirect());

        // Follow the redirection
        $crawler = $this->client->followRedirect();

        // Assert something about the redirected page
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !", $crawler->filter('h1')->text());

        // Return the client to reuse the authenticated user it in others functional tests
        return $this->client;
    }

    public function testLoginAsAdmin()
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

        // Check if the response is a redirection (status code 302)
        static::assertTrue($this->client->getResponse()->isRedirect());

        // Follow the redirection
        $crawler = $this->client->followRedirect();

        // Assert something about the redirected page only admin can access
        static::assertSame(200, $this->client->getResponse()->getStatusCode());
        static::assertSame("Gestion des utilisateurs", $crawler->filter('a[href="/users"]')->text());

        // Return the client to reuse the authenticated user it in others functional tests
        return $this->client;
    }

    public function testLoginWithWrongCredidentials()
    {
        $crawler = $this->client->request('GET', '/login');
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());

        $form = $crawler->selectButton('Connexion')->form();
        $form['_username'] = 'user@example.org';
        $form['_password'] = 'WrongPassword';
        $this->client->submit($form); 

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if error message is displayed
        static::assertSame("Identifiants invalides.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testLogout()
    {
        $this->client->request('GET', '/logout');
        static::assertTrue($this->client->getResponse()->isRedirect());

        $crawler = $this->client->followRedirect();
        static::assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if connection button present
        static::assertSame("Se connecter", $crawler->filter('a.btn.btn-success')->text());
    }
}