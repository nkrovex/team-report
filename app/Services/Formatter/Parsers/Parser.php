<?php

namespace App\Services\Formatter\Parsers;

use Illuminate\Support\Str;

abstract class Parser
{
    /**
     * @param mixed $data
     */
    abstract public function __construct($data);

    /**
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    /**
     * @param mixed $data
     * @param null $structure
     * @param string|null $basenode
     * @param string|null $encoding
     * @param bool $formatted
     * @return string
     */
    private function xmlify($data, $structure = null, ?string $basenode = 'xml', ?string $encoding = 'utf-8', bool $formatted = false): string
    {
        // turn off compatibility mode as simple xml throws a wobbly if you don't.
        if (ini_get('zend.ze1_compatibility_mode') == 1) {
            ini_set('zend.ze1_compatibility_mode', 0);
        }

        if ($structure == null) {
            $structure = simplexml_load_string("<?xml version='1.0' encoding='$encoding'?><$basenode />");
        }

        // Force it to be something useful
        if (!is_array($data) && !is_object($data)) {
            $data = (array)$data;
        }

        foreach ($data as $key => $value) {
            // checking for xml tag having attributes
            if ($key === '@attributes') {
                //STRICT IS NECESSARY because if key is numeric @attributes will be cast to integer and 0 == 0!
                foreach ($data[$key] as $attrName => $attrValue) {
                    $structure->addAttribute($attrName, $attrValue);
                }
            } else {
                // convert our booleans to 0/1 integer values, so they are
                // not converted to blank.
                if (is_bool($value)) {
                    $value = (int)$value;
                }

                // no numeric keys in our xml please!
                if (is_numeric($key)) {
                    // make string key...
                    if (isset($value['@name']) && is_string($value['@name'])) {
                        $key = $value['@name'];
                    } else {
                        $key = (Str::singular($basenode) != $basenode) ? Str::singular($basenode) : 'item';
                    }

                    unset($value['@name']);
                }

                // replace anything not alphanumeric AND '@' because of '@attributes'
                $key = preg_replace('/[^a-z_@\-0-9]/i', '', $key);

                // if there is another array found recursively call this function
                if (is_array($value) or is_object($value)) {
                    $node = $structure->addChild($key);

                    // recursive call if value is not empty
                    if (!empty($value)) {
                        $this->xmlify($value, $node, $key);
                    }
                } else {
                    // add single node.
                    $value = htmlspecialchars(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES);
                    $structure->addChild($key, $value);
                }
            }
        }

        // return formatted xml
        if ($formatted) {
            $dom = dom_import_simplexml($structure)->ownerDocument;
            $dom->formatOutput = true;
            return $dom->saveXML();
        }

        // pass back as string. or simple xml object if you want!
        return $structure->asXML();
    }

    /**
     * @param string $baseNode
     * @param string $encoding
     * @param bool $formatted
     * @return string
     */
    public function toXml(string $baseNode = 'xml', string $encoding = 'utf-8', bool $formatted = false): string
    {
        return $this->xmlify($this->toArray(), null, $baseNode, $encoding, $formatted);
    }
}
