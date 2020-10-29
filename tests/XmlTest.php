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

    const ROOT_XML_ELEMENT = '<?xml version="1.0" encoding="UTF-8"?><resource/>';

    /**
     * @test
     */
    public function arrayToXml()
    {
        $xml = Xml::arrayToXml(self::TEST_ARRAY, simplexml_load_string(self::ROOT_XML_ELEMENT));

        $this->assertXmlStringEqualsXmlFile('./tests/data/resource.xml', $xml->asXML());
    }

    /**
     * @test
     */
    public function arrayToXmlAttribute()
    {
        $xml = simplexml_load_string(self::ROOT_XML_ELEMENT);
        $xml->addAttribute('type', 'clubs');
        $xmlAttr = Xml::arrayToXmlAttribute(self::TEST_ARRAY, $xml);

        $this->assertXmlStringEqualsXmlFile('./tests/data/resource_attr.xml', $xmlAttr->asXML());
    }

    /**
     * @test
     */
    public function toArray()
    {
        $xml = simplexml_load_file('./tests/data/resource.xml');
        $array = Xml::toArray($xml->clubs);

        $this->assertIsArray($array);
        $this->assertEquals(self::TEST_ARRAY, $array);
    }

    /**
     * @test
     */
    public function toJson()
    {
        $xml = simplexml_load_file('./tests/data/resource.xml');
        $json = Xml::toJson($xml);

        $this->assertJson($json);
        $this->assertJsonStringEqualsJsonFile('./tests/data/resource.json', $json);
    }

    /**
     * @test
     */
    public function to_json_from_xml_with_value_at_attributes(): void
    {
        $xml = simplexml_load_file('./tests/data/resource_attr.xml');
        $json = Xml::toJson($xml);

        $this->assertJson($json);
        $this->assertJsonStringEqualsJsonFile('./tests/data/resource_attr.json', $json);
    }
}
