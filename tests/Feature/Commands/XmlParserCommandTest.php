<?php

namespace Tests;

use FGhazaleh\Commands\XmlParserCommand;
use FGhazaleh\Reader\Xml\XmlReader;
use FGhazaleh\Support\Collection\Collection;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Fixtures\InMemoryStorage;

class XmlParserCommandTest extends PHPUnitTestCase
{

    /**
     * @test
     * */
    public function runConsoleCommand()
    {
        $applicationPath = realpath(__DIR__.'/../../../');

        $storage = new InMemoryStorage();

        $app = new Application();
        $app->add(
            new XmlParserCommand(
                new XmlReader(['xml_element_filter' => '/data/item']),
                $storage
                )
        );

        //get console command
        $command = $app->find('parse');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['path' => $applicationPath.'/tests/Fixtures/data/file.xml']);

        //asserts
        $this->assertContains(
            "This process may take time to finish, please wait...",
            $commandTester->getDisplay()
        );

        $this->assertInstanceOf(Collection::class,$storage->read('file.xml'));
        $this->assertCount(3,$storage->read('file.xml'));

    }

}