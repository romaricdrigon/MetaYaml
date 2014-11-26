<?php

namespace RomaricDrigon\MetaYaml\Loader;

interface LoaderInterface
{
    public function load($string);

    public function loadFromFile($filename);
}
