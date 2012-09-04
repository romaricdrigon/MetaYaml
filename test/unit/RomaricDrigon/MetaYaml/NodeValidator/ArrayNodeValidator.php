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
                    'a' => array('_type' => 'text', '_strict' => true),
                    'b' => array('_type' => 'text')
                )
            ))
            ->then
                ->boolean($object->validate('toto', $config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->boolean($object->validate('toto', $config, array()))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('toto', $config, 'test'); })
                    ->hasMessage('The node "toto" is not an array')
                ->exception(function() use($object, $config) { $object->validate('toto', $config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage('The node "toto.a" is not a text value')
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
                ->exception(function() use($object, $config) { $object->validate('toto', $config, array()); })
                ->hasMessage('The node "toto" can not be empty')
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
                ->exception(function() use($object, $config) { $object->validate('test', $config, array('a' => 'test', 'b' => 'test2')); })
                    ->hasMessage('The node "test" has not allowed extra key(s): test2')
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