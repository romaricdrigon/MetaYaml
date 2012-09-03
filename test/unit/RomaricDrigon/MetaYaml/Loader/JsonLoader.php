<?php

namespace test\unit\RomaricDrigon\MetaYaml\Loader;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\Loader\JsonLoader as testedClass;

class JsonLoader extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($object = new testedClass())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\JsonLoader')
                ->array($object->loadFromFile('test/data/TestBasic/TestBase.json'))
                    ->isEqualTo(array(
                        'fleurs' => array(
                            'rose' => 'une rose',
                            'violette' => 'une violette'
                        )
                    ));
    }
}