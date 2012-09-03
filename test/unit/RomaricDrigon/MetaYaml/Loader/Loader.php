<?php

namespace test\unit\RomaricDrigon\MetaYaml\Loader;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\Loader\Loader as testedClass;

class Loader extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($object = new \mock\RomaricDrigon\MetaYaml\Loader\Loader())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\Loader')
                ->string($object->loadFromFile('test/data/TestBasic/TestBase.yml'))
                    ->isEqualTo("fleurs:\n    rose: une rose\n    violette: une violette");
    }

    public function testFileNotFound()
    {
        $this
            ->if($object = new \mock\RomaricDrigon\MetaYaml\Loader\Loader())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\Loader')
                ->exception(function() use ($object) { $object->loadFromFile('fileNotFound'); })
                    ->hasMessage("The file 'fileNotFound' was not found");
    }
}