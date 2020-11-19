<?php


namespace App\Tests\Api;


use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseTest extends WebTestCase
{

    public static function tearDownAfterClass()
    {
        self::reloadDataFixtures();
    }

    protected function setUp()
    {
        self::reloadDataFixtures();

    }

    private static function reloadDataFixtures(): void
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $encoder = $kernel->getContainer()->get('security.password_encoder');


        $loader = new Loader();
        foreach (self::getFixtures($encoder) as $fixture) {
            $loader->addFixture($fixture);
        }

        $purger = new ORMPurger();
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());
    }
    protected static function getFixtures($encoder): iterable
    {
        return [
            new UserFixtures($encoder),
        ];
    }
}