<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerFunctionalTest extends WebTestCase
{

    protected function setUp()
    {
        static::createClient();

        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->createDb($em);

        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadFile(__DIR__ . '/../Fixtures/fixtures.yml')->getObjects();

        foreach($objectSet as $object) {
            $em->persist($object);
        }

         $em->flush();

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testLoadUserAction()
    {
        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("GET","/user/Ailali");

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }


    public function testDeleteUser()
    {
        $client = self::$kernel->getContainer()->get('test.client');

        $client->request("DELETE","/user/Ailali");

        $this->assertSame(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

    }


    private function createDb(EntityManager $em)
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $classes = [];
        /** @var ClassMetadata $class */
        foreach ($em->getMetadataFactory()->getAllMetadata() as $class) {
            $classes[] = $class;
        }

        $tool->dropSchema($classes);
        $tool->createSchema($classes);
    }



}