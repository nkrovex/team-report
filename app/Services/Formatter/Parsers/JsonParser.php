<?php

namespace App\Services\Formatter\Parsers;

class JsonParser extends Parser
{
    private $json;

    public function __construct($data)
    {
        $this->json = json_decode(trim($data), true);
    }

    public function toArray(): array
    {
        return $this->json;
    }
}
