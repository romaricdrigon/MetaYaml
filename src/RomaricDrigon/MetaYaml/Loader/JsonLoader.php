<?php

namespace RomaricDrigon\MetaYaml\Loader;

class JsonLoader extends Loader
{
    public function load($string)
    {
        return json_decode($string, true);
    }

    public function loadFromFile($filename)
    {
        $json = parent::loadFromFile($filename);

        return $this->load($json);
    }
}
