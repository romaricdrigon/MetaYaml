<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\PartialNodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class PartialNodeValidator extends atoum\test
{
    // we can't test much, because validate method
    // will return to SchemaValidator (which we will test later)
    public function testAll()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new testedClass($schema_validator))
            ->and($config = array(
                '_metadata' => array(),
                '_partial' => 'nom_partial'
            ))
            ->then
                ->object($object)
                    ->isInstanceOf('RomaricDrigon\\MetaYaml\\NodeValidator\\PartialNodeValidator')
                ->exception(function() use ($object, $config) { $object->validate('test', $config, array()); })
                    ->hasMessage("You're using a partial but partial 'nom_partial' is not defined in your schema")
        ;
    }
}