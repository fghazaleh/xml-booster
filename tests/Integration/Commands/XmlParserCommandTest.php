<?php

namespace Tests;

use FGhazaleh\Commands\XmlParserCommand;
use FGhazaleh\Configuration\ConfigurationLoader;
use FGhazaleh\Reader\Xml\XmlReader;
use FGhazaleh\Storage\GoogleSheets\GoogleClientFactory;
use FGhazaleh\Support\Collection\Collection;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Fixtures\GoogleSheetsStorageMock;

class XmlParserCommandTest extends PHPUnitTestCase
{
    /**
     * @test
     * */
    public function runConsoleCommand()
    {
        $applicationPath = realpath(__DIR__ . '/../../../');

        //create configuration instance.
        $config = new ConfigurationLoader($applicationPath);

        //create GoogleSheets storage instance.
        $storage = new GoogleSheetsStorageMock(GoogleClientFactory::make($config->get('google_api.sheets')));

        //
        $reader = new XmlReader(['xml_element_filter' => '/data/item']);

        //create Console Application
        $app = new Application();
        $app->add(new XmlParserCommand($reader, $storage));

        //get console command
        $command = $app->find('parse');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['path' => $applicationPath . '/tests/Fixtures/data/file.xml']);

        $spreadsheetId = $storage->getLastInsertedId();

        //read data from Google spreadsheet.
        $data = $storage->read($spreadsheetId);

        //asserts
        $this->assertInstanceOf(Collection::class, $data);
        $this->assertCount(4, $data);
        $this->assertSame(['Franco', 'Male', 'Rome'],$data[1]);


        //delete from Google Drive
        $storage->remove($spreadsheetId);

    }
}
