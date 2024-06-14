<?php

namespace App\Tests\Controller;

use App\DataFixtures\TestFixtures;
use App\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndexActionWithoutLogin()
    {
        // If the user isn't logged, should still be able to access the homepage
        $client = static::createClient();
        $client->request('GET', '/');
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }
}