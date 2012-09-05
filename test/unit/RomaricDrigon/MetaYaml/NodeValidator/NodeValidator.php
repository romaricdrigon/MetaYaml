<?php

namespace test\unit\RomaricDrigon\MetaYaml\NodeValidator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\NodeValidator\NodeValidator as testedClass;
use RomaricDrigon\MetaYaml\SchemaValidator;

class NodeValidator extends atoum\test
{
    public function testDefault()
    {
        $this
            ->if($schema_validator = new SchemaValidator())
            ->and($object = new \mock\RomaricDrigon\MetaYaml\NodeValidator\NodeValidator($schema_validator))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\NodeValidator\\NodeValidator')
        ;
    }
}