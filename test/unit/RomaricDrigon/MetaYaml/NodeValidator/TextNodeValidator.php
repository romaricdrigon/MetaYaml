<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\TextNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class TextNodeValidator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_not_empty' => true))
            ->then
                ->boolean($object->validate('toto', $config, 'test'))->isEqualTo(true)
                ->boolean($object->validate('toto', $config, '0'))->isEqualTo(true)
                ->boolean($object->validate('toto', $config, 'false'))->isEqualTo(true)
                ->boolean($object->validate('toto', $config, null))->isEqualTo(true) // is not required, so null is ok
                ->boolean($object->validate('toto', $config, true))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, ''); })
                    ->hasMessage("The node 'test' can not be empty")
            ->if($config = array('_not_empty' => false))
                ->boolean($object->validate('test', $config, ''))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, array()); })
                    ->hasMessage("The node 'test' is not a text value")
        ;
    }

    public function testRequired()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_required' => true))
            ->then
                ->boolean($object->validate('toto', $config, 'test'))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, null); })
                    ->hasMessage("The node 'test' is required")
        ;
    }

    public function testStrict()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_strict' => true))
            ->then
                ->boolean($object->validate('toto', $config, 'test'))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, true); })
                    ->hasMessage("The node 'test' is not a text value")
        ;
    }
}