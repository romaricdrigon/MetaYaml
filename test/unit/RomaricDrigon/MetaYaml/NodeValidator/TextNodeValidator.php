<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\TextNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class TextNodeValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array('_metadata' => array('_not_empty' => true)))
            ->then
                ->boolean($object->validate('toto', $config, 'test'))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate('toto', $config, true); })
                    ->hasMessage('The node "toto" is not a string')
                ->exception(function() use($object, $config) { $object->validate('toto', $config, ''); })
                    ->hasMessage('The node "toto" can not be empty')
            ->if($config = array('_metadata' => array('_not_empty' => false)))
                ->boolean($object->validate('toto', $config, ''))->isEqualTo(true);
    }
}