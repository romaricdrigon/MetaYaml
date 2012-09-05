<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\BooleanNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class BooleanNodeValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_required' => true))
            ->then
                ->boolean($object->validate('toto', $config, true))->isEqualTo(true)
                ->boolean($object->validate('toto', $config, 'false'))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('toto', $config, 'test'); })
                    ->hasMessage("The node 'toto' is not a boolean")
                ->exception(function() use($object, $config) { $object->validate('toto', $config, null); })
                    ->hasMessage("The node 'toto' is required")
            ->if($config = array('_required' => false))
                ->boolean($object->validate('toto', $config, null))->isEqualTo(true)
        ;
    }

    public function testStrict()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_required' => true, '_strict' => true))
            ->then
                ->boolean($object->validate('test', $config, false))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'true'); })
                    ->hasMessage("The node 'test' is not a boolean")
        ;
    }
}