<?php

namespace RomaricDrigon\MetaYaml\Loader;

abstract class Loader implements LoaderInterface
{
    public function loadFromFile($filename)
    {
        if (! file_exists($filename)) {
            throw new \Exception("The file '$filename' was not found");
        }

        return file_get_contents($filename);
    }
}
