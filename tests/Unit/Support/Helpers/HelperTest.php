<?php

namespace Tests;

use FGhazaleh\Support\Helpers\Helper;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class HelperTest extends PHPUnitTestCase
{

    /**
     * @test
     * @dataProvider dataProviderFileExistsTest
     * @param $file
     * @param $value
     */
    public function testFileExists($file,$value)
    {
        $this->assertSame(Helper::fileExists($file),$value);
    }

    public function dataProviderFileExistsTest()
    {
        return [
            ['http://example.com/file.xml',false],
            [__DIR__.'/../../../Fixtures/data/file.xml',true],
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderIsRemoteFileTest
     * @param $file
     * @param $value
     */
    public function testIsRemoteFile($file,$value)
    {
        $this->assertSame(Helper::isRemoteFile($file),$value);
    }

    public function dataProviderIsRemoteFileTest()
    {
        return [
            ['http://example.com/file.xml',true],
            ['https://example2.com/file.xml',true],
            ['/var/www/file.xml',false],
        ];
    }
}