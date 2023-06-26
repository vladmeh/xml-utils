<?php

namespace Vladmeh\XmlUtils;

use Vladmeh\XmlUtils\Support\Macroable;

class Xml
{
    use Macroable;

    const NODE_NAME = 'item';

    /**
     * @param array $array
     * @param \SimpleXMLElement $element
     * @param bool $upperNode
     * @param string $nodeName
     * @return \SimpleXMLElement
     */
    public static function arrayToXmlAttribute(
        array             $array,
        \SimpleXMLElement $element,
        bool              $upperNode = false,
        string            $nodeName = self::NODE_NAME
    ): \SimpleXMLElement
    {
        foreach ($array as $key => $value) {
            self::checkKey($key, $upperNode, $nodeName);

            if (is_array($value)) {
                $node = is_string($key) ? $element->addChild($key) : $element;
                self::arrayToXmlAttribute($value, $node, $upperNode, $nodeName);
            } else {
                $element->addAttribute(\mb_strtolower($key, 'UTF-8'), $value);
            }
        }

        return $element;
    }

    /**
     * @param $key
     * @param bool $upperNode
     * @param string $nodeName
     */
    private static function checkKey(&$key, bool $upperNode, string $nodeName = self::NODE_NAME): void
    {
        $key = is_numeric($key) ? $nodeName : $key;
        $key = $upperNode ? \mb_strtoupper($key, 'UTF-8') : $key;
    }

    /**
     * @param array $data
     * @param \SimpleXMLElement $element
     * @param false $upperNode
     * @param string $nodeName
     * @return \SimpleXMLElement
     */
    public static function arrayToXml(
        array             $data,
        \SimpleXMLElement $element,
        bool              $upperNode = false,
        string            $nodeName = self::NODE_NAME
    ): \SimpleXMLElement
    {
        foreach ($data as $key => $value) {
            self::checkKey($key, $upperNode, $nodeName);

            if (is_array($value)) {
                $child = $element->addChild($key);
                self::arrayToxml($value, $child, $upperNode, $nodeName);
            } else {
                $element->addChild($key, $value);
            }
        }

        return $element;
    }

    /**
     * @param \SimpleXMLElement|\SimpleXMLElement[] $xml
     *
     * @return false|string
     */
    public static function toJson($xml)
    {
        return json_encode(self::toArray($xml), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
    }

    /**
     * @param $data
     * @param null $result
     * @param int $recursionDepth
     *
     * @return mixed
     */
    public static function toArray($data, &$result = null, int &$recursionDepth = 0)
    {
        if (is_object($data)) {
            if ($recursionDepth == 0) {
                $callerProviderObject = $data;
            }
            $data = get_object_vars($data);
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $res = null;

                $recursionDepth++;
                self::toArray($value, $res, $recursionDepth);
                $recursionDepth--;

                if ($key === '@attributes' || is_array($value)) {
                    $result = $res;
                } else {
                    $result[strtolower($key)] = $res;
                }
            }

            if ($recursionDepth == 0) {
                $temp = $result;
                $result = [];
                if (isset($callerProviderObject)) {
                    $result[strtolower($callerProviderObject->getName())] = $temp;
                } elseif (is_array($temp)
                    && count($temp) == 1
                    && array_key_exists(0, $temp)
                ) {
                    $result = $temp[array_key_first($temp)];
                } else {
                    $result = $temp;
                }
            }
        } else {
            $result = $data;
        }

        return $result;
    }
}
