<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\MetaYaml as testedClass;

class MetaYaml extends atoum\test
{
    public function testConstruct()
    {
        $this
            ->if($yaml = '')
            ->and($object = new testedClass())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml');
    }

    public function testForgottenSchemaLoad()
    {
        $this
            ->if($yaml = file_get_contents('test/data/TestBasicRequired.yml'))
            ->and($object = new testedClass())
            ->then
                ->exception(function() use ($object, $yaml) { $object->validateYaml($yaml); })
                ->hasMessage('You should set schema, via loadSchema() or loadSchemaFromYaml, first !');
    }

    // the basic case - ok
    public function testBasic()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestBasicReference.yml'))
            ->and($yaml = file_get_contents('test/data/TestBasicBase.yml'))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->boolean($object->validateYaml($yaml))->isEqualTo(true);
    }
}