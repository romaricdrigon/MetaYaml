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
                '_children' => array(
                    'a' => array('_type' => 'text', '_strict' => true, '_required' => true),
                    'b' => array('_type' => 'text')
                )
            ))
            ->then
                ->boolean($object->validate('test', $config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, array('a' => 'value', 'z' => null)); })
                    ->hasMessage("The node 'test' has not allowed extra key(s): z")
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'test'); })
                    ->hasMessage("The node 'test' is not an array")
                ->exception(function() use($object, $config) { $object->validate('test', $config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage("The node 'test.a' is not a text value")
                ->exception(function() use($object, $config) { $object->validate('test', $config, array()); })
                    ->hasMessage("The node 'test.a' is required")
        ;
    }

    public function testNotEmpty()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_not_empty' => true,
                '_children' => array(
                    'a' => array('_type' => 'text', '_strict' => true),
                    'b' => array('_type' => 'text')
                )
            ))
            ->then
                ->boolean($object->validate('toto', $config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, false); })
                    ->hasMessage("The node 'test' is not an array")
                ->exception(function() use($object, $config) { $object->validate('test', $config, array()); })
                    ->hasMessage("The node 'test' can not be empty")
        ;
    }

    public function testExtraKeys()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_children' => array(
                    'a' => array('_type' => 'text')
                )
            ))
            ->then
                ->boolean($object->validate('test', $config, array('a' => 'test')))->isEqualTo(true)
                ->boolean($object->validate('test', $config, array()))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, array('a' => 'test', 'b' => 'test2')); })
                    ->hasMessage("The node 'test' has not allowed extra key(s): b")
        ;
    }

    public function testIgnoreExtraKeys()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_ignore_extra_keys' => true,
                '_children' => array(
                    'a' => array('_type' => 'text')
                )
            ))
            ->then
            ->boolean($object->validate('test', $config, array('a' => 'test', 'b' => 'test2')))->isEqualTo(true)
        ;
    }
}