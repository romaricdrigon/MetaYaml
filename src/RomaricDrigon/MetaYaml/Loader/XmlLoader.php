<?php

namespace RomaricDrigon\MetaYaml\Loader;

class XmlLoader extends Loader
{
    public function load($string, $ignore_first_node = false)
    {
        // we will parse XML using the convention (cf doc.)

        // elements and attributes are stored in arrays
        // multiple elements will give a prototype, using "_key" as name -> else nodes will be merged
        // namespace are ditched
        // empty nodes are suppressed
        // node _value

        if (! $ignore_first_node) {
            $string = '<mock_tag>'.$string.'</mock_tag>';
        }

        $sxi = simplexml_load_string($string, 'SimpleXmlIterator');

        if ($sxi === false) {
            throw new \Exception('Error in XmlLoader : XML seems to be invalid');
        }

        return $this->xmlToArray($sxi);
    }

    public function loadFromFile($filename)
    {
        $xml = parent::loadFromFile($filename);

        return $this->load($xml);
    }

    /*
     * Private
     * XML parser
     */
    private function xmlToArray(\SimpleXMLIterator $sxi)
    {
        $a = array();

        for ($sxi->rewind(); $sxi->valid(); $sxi->next()) {
            $t = array();
            $current = $sxi->current();
            $attributes = $current->attributes();
            $name = isset($attributes->_key) ? strval($attributes->_key) : $sxi->key();

            // save attributes
            foreach ($attributes as $att_key => $att_value) {
                if ($att_key !== '_key') {
                    $t[$att_key] = strval($att_value);
                }
            }

            // we parse nodes
            if ($sxi->hasChildren()) { // children
                $t = array_merge($t, $this->xmlToArray($current));
            } else { // it's a leaf
                if (empty($t)) {
                    $t = strval($current); // strval will call _toString()
                } else {
                    $t['_value'] = strval($current); // strval will call _toString()
                }
            }

            $a[$name] = $t;
        }

        return $a;
    }
}
