<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
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
        $this->manager->getConnection()->executeStatement('DELETE FROM task WHERE id > 3');
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

    public function testListActionWithoutLogin()
    {
        // If the user isn't logged, should see the tasks
        $this->client->request('GET', '/tasks');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testListAction()
    {
        $this->loginAsUser();

        $this->client->request('GET', '/tasks');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $this->loginAsUser();

        $crawler = $this->client->request('GET', '/tasks/create');
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if creation page field exists
        $this->assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        $this->assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Nouvelle tâche';
        $form['task[content]'] = 'Ceci est une tâche crée par un test';
        $this->client->submit($form); 
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if success message is displayed
        $this->assertStringContainsString("Success! La tâche a bien été ajoutée.", $crawler->filter('div.alert.alert-success')->text());

    }

    public function testDeleteAction()
    {
        $this->loginAsUser();

        // Get the last ID in the table task
        $sql = 'SELECT MAX(id) as max_id FROM task';
        $stmt = $this->manager->getConnection()->executeQuery($sql);
        $id = $stmt->fetchOne();

        $this->client->request('GET', "/tasks/$id/delete");
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if success message is displayed
        $this->assertStringContainsString("Success! La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
    }

    public function testDeleteActionWhereSimpleUserIsNotAuthor()
    {
        $this->loginAsUser();

        $this->client->request('GET', '/tasks/2/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if error message is displayed
        $this->assertStringContainsString("Error! Vous n'avez pas les permissions nécessaires pour supprimer cette tâche.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testDeleteActionWithSimpleUserWhereAuthorIsAnonymous()
    {
        $this->loginAsUser();

        $this->client->request('GET', '/tasks/3/delete');
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Test if error message is displayed
        $this->assertStringContainsString("Error! Vous n'avez pas les permissions nécessaires pour supprimer cette tâche.", $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testDeleteActionWithAdminUserWhereAuthorIsAnonymous()
    {
        // Login as admin
        $this->loginAsAdmin();

        // Create a task with a specific structure
        $sql = "INSERT INTO `task` (`id`, `created_at`, `title`, `content`, `is_done`, `user_id`) VALUES (4, '2024-06-07 08:32:44.000000', 'Tâche 4', 'Tâche exemple créée par un utilisateur anonyme', 0, 1)";
        $this->manager->getConnection()->executeQuery($sql);

        // Delete the newly created task
        $this->client->request('GET', "/tasks/4/delete");
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Check that success message is displayed
        $this->assertStringContainsString("Success! La tâche a bien été supprimée.", $crawler->filter('div.alert.alert-success')->text());
    }

    public function testDeleteActionWhereItemDontExists()
    {
        $this->loginAsUser();

        $this->client->request('GET', '/tasks/-100/delete');
        $this->assertSame(404, $this->client->getResponse()->getStatusCode());
    }

    public function testEditAction()
    {
        // Login as user
        $this->loginAsUser();

        // Create a new task for editing
        $sql = "INSERT INTO `task` (`id`, `created_at`, `title`, `content`, `is_done`, `user_id`) VALUES (5, '2024-06-07 08:32:44.000000', 'Tâche 5', 'Tâche exemple créée par un utilisateur anonyme', 0, 1)";
        $this->manager->getConnection()->executeQuery($sql);

        // Access edit action with newly created task
        $crawler = $this->client->request('GET', "/tasks/5/edit");
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Check if form is correctly displayed
        $this->assertCount(1, $crawler->filter('form[name="task"]'));

        // Submit form with new data
        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Nouveau titre';
        $form['task[content]'] = 'Nouveau contenu';
        $this->client->submit($form);

        // Check if redirection is correct after edit
        $this->assertTrue($this->client->getResponse()->isRedirect('/tasks'));

         // Follow redirection
        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Check if success message is displayed
        $this->assertStringContainsString("Success! La tâche a bien été modifiée.", $crawler->filter('div.alert.alert-success')->text());
        $this->cleanDB();
    }

    public function testToggleTaskAction()
    {
        // Login as user
        $this->loginAsUser();

        // Access toggle action with existing task
        $this->client->request('GET', "/tasks/1/toggle");
        $this->assertSame(302, $this->client->getResponse()->getStatusCode());

        // Follow redirection
        $crawler = $this->client->followRedirect();
        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

        // Check if success message is displayed
        $this->assertStringContainsString("Success! La tâche Tâche 1 a bien été marquée comme faite.", $crawler->filter('div.alert.alert-success')->text());
    }

}