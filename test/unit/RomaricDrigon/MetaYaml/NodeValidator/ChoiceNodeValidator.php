<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\ChoiceNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class ChoiceNodeValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_required' => true,
                '_choices' => array(
                    'a' => array('_type' => 'number'),
                    'b' => array('_type' => 'boolean')
                )
            ))
            ->then
                ->boolean($object->validate('toto', $config, 10))->isEqualTo(true)
                ->boolean($object->validate('toto', $config, true))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('test', $config, 'test'); })
                    ->hasMessage("The choice node 'test' is invalid with error: The node 'test' is not a number")
        ;
    }
}