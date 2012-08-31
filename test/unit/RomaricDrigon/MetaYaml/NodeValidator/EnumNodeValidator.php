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
            ->and($config = array('_metadata' => array('_required' => true), '_values' => array('toto', 'tata')))
            ->then
                ->boolean($object->validate('toto', $config, 'toto'))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('toto', $config, 'test'); })
                    ->hasMessage('The value of the node "toto" is not allowed');
    }
}