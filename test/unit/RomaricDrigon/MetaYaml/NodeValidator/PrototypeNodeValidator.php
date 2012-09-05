<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\PrototypeNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class PrototypeNodeValidator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_prototype' => array('_type' => 'number')))
            ->then
                ->boolean($object->validate('test', $config, array('a' => 10, 'b' => 5)))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'test'); })
                    ->hasMessage("The node 'test' is not an array")
                ->exception(function() use($object, $config) { $object->validate('test', $config, array('a' => 10, 'b' => 'test')); })
                    ->hasMessage("The node 'test.b' is not a number")
        ;
    }

    public function testMinMax()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_min_items' => 2,
                '_max_items' => 2,
                '_prototype' => array('_type' => 'number')
            ))
            ->then
                ->boolean($object->validate('test', $config, array('a' => 10, 'b' => 5)))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, array('a' => 10)); })
                    ->hasMessage("Prototype node 'test' has not enough children")
                ->exception(function() use($object, $config) { $object->validate('test', $config, array('a' => 10, 'b' => 5, 'c' => 2)); })
                    ->hasMessage("Prototype node 'test' has too much children")
        ;
    }
}