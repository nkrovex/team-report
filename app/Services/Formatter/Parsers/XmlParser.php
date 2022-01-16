<?php

namespace App\Services\Formatter\Parsers;

class XmlParser extends Parser
{
    private $xml;

    private function objectify($value): array
    {
        if (is_string($value)) {
            $temp = simplexml_load_string($value, 'SimpleXMLElement', LIBXML_NOCDATA);
        } else {
            $temp = $value;
        }

        $result = [];

        foreach ((array)$temp as $key => $value) {
            if ($key === "@attributes") {
                $result['_' . key($value)] = $value[key($value)];
            } elseif (is_array($value) && count($value) < 1) {
                $result[$key] = '';
            } else {
                $result[$key] = (is_array($value) or is_object($value)) ? $this->objectify($value) : $value;
            }
        }

        return $result;
    }

    public function __construct($data)
    {
        $this->xml = $this->objectify($data);
    }

    public function toArray(): array
    {
        return $this->xml;
    }
}
