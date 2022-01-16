<?php

namespace App\Services\Formatter;

use App\Services\Formatter\Parsers\ArrayParser;
use InvalidArgumentException;
use App\Services\Formatter\Parsers\Parser;
use App\Services\Formatter\Parsers\JsonParser;
use App\Services\Formatter\Parsers\XmlParser;

class Formatter
{
    const ARR  = 'array';
    const JSON = 'json';
    const XML = 'xml';

    private static $supportedTypes = [self::ARR, self::JSON, self::XML];

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @param mixed $data
     * @param string $type
     * @return Formatter
     */
    public static function make($data, string $type): Formatter
    {
        if (in_array($type, self::$supportedTypes)) {
            $parser = null;
            switch ($type) {
                case self::ARR:
                    $parser = new ArrayParser($data);
                    break;
                case self::JSON:
                    $parser = new JsonParser($data);
                    break;
                case self::XML:
                    $parser = new XmlParser($data);
                    break;
            }
            return new Formatter($parser);
        }

        throw new InvalidArgumentException(
            'make function only accepts [array, json, xml] for $type but ' . $type . ' was provided.'
        );
    }

    private function __construct($parser)
    {
        $this->parser = $parser;
    }

    public function toArray(): array
    {
        return $this->parser->toArray();
    }

    public function toJson(): string
    {
        return $this->parser->toJson();
    }

    public function toXml($baseNode = 'xml', $encoding = 'utf-8', $formatted = false): string
    {
        return $this->parser->toXml($baseNode, $encoding, $formatted);
    }
}
