<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\ArrayNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class ArrayNodeValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_required' => true,
                '_content' => array(
                    'a' => array('_type' => 'text', '_strict' => true),
                    'b' => array('_type' => 'text')
                )
            ))
            ->then
                ->boolean($object->validate('toto', $config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('toto', $config, 'test'); })
                    ->hasMessage('The node "toto" is not an array')
                ->exception(function() use($object, $config) { $object->validate('toto', $config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage('The node "toto.a" is not a text value');
    }
}