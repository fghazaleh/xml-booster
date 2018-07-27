<?php

namespace Tests;

use FGhazaleh\Exceptions\FileNotFoundException;
use FGhazaleh\Reader\Xml\XmlReader;
use FGhazaleh\Support\Collection\Collection;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class XmlReaderTest extends PHPUnitTestCase
{
    /**
     * @test
     * */
    public function it_should_read_xml_file_and_return_data()
    {
        $file = __DIR__.'/../../../Fixtures/data/file.xml';

        $xmlReader = new XmlReader(['xml_element_filter' => '/data/item']);
        $data = $xmlReader->read($file);

        $this->assertInstanceOf(Collection::class,$data);
        $this->assertCount(3,$data);
        $this->assertSame([
            'name'=>'Franco',
            'gender' => 'Male',
            'city' => 'Rome'
        ],$data[0]);
    }

    /**
     * @test
     * */
    public function it_should_throws_an_exceptions_when_trying_to_read_not_exists_file()
    {
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage('File not found in provided path [data/not_found_file.xml]');

        $file ='data/not_found_file.xml';
        $xmlReader = new XmlReader(['xml_element_filter' => '/data/item']);
        $xmlReader->read($file);
    }
}