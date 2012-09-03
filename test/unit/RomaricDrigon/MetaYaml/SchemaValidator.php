<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\SchemaValidator as testedClass;

class SchemaValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array('_root' => array(
                '_metadata' => array('_required'=> true), 
                '_content' => array(
                    'a' => array('_metadata' => array('_type' => 'text', '_strict' => true)),
                    'b' => array('_metadata' => array('_type' => 'text'))
            ))))
            ->then
                ->boolean($object->validate($config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate($config, 'test'); })
                    ->hasMessage('The node "root" is not an array')
                ->exception(function() use($object, $config) { $object->validate($config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage('The node "root.a" is not a string');
    }
}