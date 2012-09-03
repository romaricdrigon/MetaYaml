<?php

namespace test\unit\RomaricDrigon\MetaYaml\Loader;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\Loader\YamlLoader as testedClass;

class YamlLoader extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($object = new testedClass())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\YamlLoader')
                ->array($object->loadFromFile('test/data/TestBasic/TestBase.yml'))
                    ->isEqualTo(array(
                        'fleurs' => array(
                            'rose' => 'une rose',
                            'violette' => 'une violette'
                        )
                    ));
    }
}