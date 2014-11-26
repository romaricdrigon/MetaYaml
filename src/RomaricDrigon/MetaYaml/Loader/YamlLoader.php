<?php

namespace RomaricDrigon\MetaYaml\Loader;

use Symfony\Component\Yaml\Yaml;

class YamlLoader extends Loader
{
    public function load($string)
    {
        return Yaml::parse($string);
    }

    public function loadFromFile($filename)
    {
        $yaml = parent::loadFromFile($filename);

        return $this->load($yaml);
    }
}
