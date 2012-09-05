<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\EnumNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class EnumNodeValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                    '_required' => true,
                    '_values' => array(
                        'toto',
                        5,
                        false,
                        true
                    )
                )
            )
            ->then
                ->boolean($object->validate('test', $config, 'toto'))->isEqualTo(true)
                ->boolean($object->validate('test', $config, 5))->isEqualTo(true)
                ->boolean($object->validate('test', $config, '5'))->isEqualTo(true)
                ->boolean($object->validate('test', $config, false))->isEqualTo(true)
                ->boolean($object->validate('test', $config, 'false'))->isEqualTo(true)
                ->boolean($object->validate('test', $config, true))->isEqualTo(true)
                ->boolean($object->validate('test', $config, 'true'))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, ''); })
                    ->hasMessage("The value '' is not allowed for node 'test'")
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'test'); })
                    ->hasMessage("The value 'test' is not allowed for node 'test'")
        ;
    }

    public function testWithoutTrue()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_required' => true,
                '_values' => array(
                    'toto',
                    5,
                    false
                )
            )
        )
            ->then
            ->boolean($object->validate('test', $config, 'toto'))->isEqualTo(true)
            ->boolean($object->validate('test', $config, 5))->isEqualTo(true)
            ->boolean($object->validate('test', $config, '5'))->isEqualTo(true)
            ->boolean($object->validate('test', $config, false))->isEqualTo(true)
            ->boolean($object->validate('test', $config, 'false'))->isEqualTo(true)
            ->exception(function() use($object, $config) { $object->validate('test', $config, ''); })
            ->hasMessage("The value '' is not allowed for node 'test'")
            ->exception(function() use($object, $config) { $object->validate('test', $config, true); })
            ->hasMessage("The value 'true' is not allowed for node 'test'")
            ->exception(function() use($object, $config) { $object->validate('test', $config, 'test'); })
            ->hasMessage("The value 'test' is not allowed for node 'test'")
        ;
    }

    public function testStrict()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                    '_required' => true,
                    '_strict' => true,
                    '_values' => array(
                        'toto',
                        5,
                        false,
                        true
                    )
                )
            )
            ->then
                ->boolean($object->validate('test', $config, 'toto'))->isEqualTo(true)
                ->boolean($object->validate('test', $config, 5))->isEqualTo(true)
                ->boolean($object->validate('test', $config, false))->isEqualTo(true)
                ->boolean($object->validate('test', $config, true))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'true'); })
                    ->hasMessage("The value 'true' is not allowed for node 'test'")
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'false'); })
                    ->hasMessage("The value 'false' is not allowed for node 'test'")
                ->exception(function() use($object, $config) { $object->validate('test', $config, '5'); })
                    ->hasMessage("The value '5' is not allowed for node 'test'")
        ;
    }
}