<?php
/**
 * Class PlistXMLParser
 */
class PlistXMLParser
{
    const NODE_STRING  = 'string';
    const NODE_INTEGER = 'integer';
    const NODE_DATE    = 'date';  # NOTE: not implemented
    const NODE_TRUE    = 'true';  # NOTE: not implemented
    const NODE_FALSE   = 'false'; # NOTE: not implemented
    const NODE_DATA    = 'data';  # NOTE: not implemented
    const NODE_ARRAY   = 'array'; # NOTE: not implemented
    const NODE_DICT    = 'dict';

    private $xml;

    public function loadXML($string) {

        $this->xml = simplexml_load_string($string);
    }

    public function loadSimpleXMLElement(SimpleXMLElement $element) {

        $this->xml = $element;
    }

    public function parse() {

        $result = array();

        foreach ($this->xml->key as $key) {
            list($value) = $key->xpath('followcfdi:*[1]') + [NULL];

            if ($value === NULL) {
                trigger_error(sprintf('Plist Key "%s" without value: ', $key));
                continue;
            }

            $type      = $value->getName();
            $keyString = (string)$key;
            switch ($type) {
                case self::NODE_INTEGER:
                    $result[$keyString] = sprintf('%0.0f', trim($value));
                    break;

                case self::NODE_STRING:
                    $result[$keyString] = (string)$value;
                    break;

                case self::NODE_DICT:
                    $parser = new self();
                    $parser->loadSimpleXMLElement($value);
                    $result[$keyString] = $parser->parse();
                    break;

                default:
                    throw new UnexpectedValueException(sprintf('Unexpected type "%s" for key "%s"', $type, $key));
            }
        }

        return $result;
    }
}
