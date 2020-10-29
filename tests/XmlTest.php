<?php

namespace Vladmeh\XmlUtils\Tests;

use Vladmeh\XmlUtils\Xml;

class XmlTest extends TestCase
{
    const TEST_ARRAY = [
        'clubs' => [
            [
                'id' => 1,
                'name' => 'Test Club',
                'address' => 'Test club address',
                'active' => true,
                'city' => [
                    'id' => 1,
                    'name' => 'Test city name',
                ],
            ],
            [
                'id' => 2,
                'name' => 'Тестовый клуб',
                'address' => 'Адресс тестового клуба',
                'active' => false,
                'city' => [
                    'id' => 2,
                    'name' => 'Санкт-Петербург',
                ],
            ],
        ],
    ];

    public function testArrayToXmlAttribute()
    {
        $this->markTestSkipped('Не реализован');
    }

    public function testToArray()
    {
        $xml = simplexml_load_file('./tests/data/resource.xml');
        $array = Xml::toArray($xml->clubs);

        $this->assertIsArray($array);
        $this->assertEquals(self::TEST_ARRAY, $array);
    }

    public function testToJson()
    {
        $this->markTestSkipped('Не реализован');
    }

    /**
     * @test
     */
    public function arrayToXml()
    {
        $rootElement = '<?xml version="1.0" encoding="UTF-8"?><resource/>';
        $xml = Xml::arrayToXml(self::TEST_ARRAY, simplexml_load_string($rootElement));

        $this->assertXmlStringEqualsXmlFile('./tests/data/resource.xml', $xml->asXML());
    }
}
