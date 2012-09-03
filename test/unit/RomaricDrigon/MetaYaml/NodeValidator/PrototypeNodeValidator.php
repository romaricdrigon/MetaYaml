<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\PrototypeNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class PrototypeNodeValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_prototype' => array('_type' => 'number')))
            ->then
                ->boolean($object->validate('toto', $config, array('a' => 10, 'b' => 5)))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('toto', $config, 'test'); })
                    ->hasMessage('The node "toto" is not an array')
                ->exception(function() use($object, $config) { $object->validate('toto', $config, array('a' => 10, 'b' => 'test')); })
                    ->hasMessage('The node "toto.b" is not a number');
    }
}