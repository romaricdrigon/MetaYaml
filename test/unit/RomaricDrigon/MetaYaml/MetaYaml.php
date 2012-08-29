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

    /*
     * Tests about schema structure :
     * we make sure we're able to parse properly our schema file
     */

    public function testSchemaNoRoot()
    {
        $this
            ->if($yaml = file_get_contents('test/data/TestBasicRequired.yml'))
            ->and($object = new testedClass())
            ->then
                ->exception(function() use ($object) { $object->loadSchema(array()); })
                ->hasMessage('Missing _root element for schema !');
    }

    /*
     * Tests about data validation :
     * we try to validate more and more complex structures
     */

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

    // some crazy test - ok
    public function testAdvanced()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestAdvancedReference.yml'))
            ->and($yaml = file_get_contents('test/data/TestAdvanced.yml'))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->boolean($object->validateYaml($yaml))->isEqualTo(true);
    }

    // let's check the attributes
    public function testAttributes()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestAttributesReference.yml'))
            ->and($yaml = file_get_contents('test/data/TestAttributes.yml'))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->boolean($object->validateYaml($yaml))->isEqualTo(true);
    }
    public function testAttributesRequired()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestAttributesReference.yml'))
            ->and($data = array(
                'pas vide' => 'value'
            ))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->exception(function() use ($object, $data) { $object->validate($data); })
                    ->hasMessage('The child node "requis" at path "root" must be configured.');
    }
    public function testAttributesNotEmpty()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestAttributesReference.yml'))
            ->and($data = array(
                'requis' => 'ok',
                'pas vide' => ''
            ))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->exception(function() use ($object, $data) { $object->validate($data); })
                    ->hasMessage('The path "root.pas vide" cannot contain an empty value, but got "".');
    }
//    public function testAttributesNotEmptyArray()
//    {
//        $this
//            ->if($schema = file_get_contents('test/data/TestAttributesReference.yml'))
//            ->and($data = array(
//                'requis' => 'ok',
//                'pas vide' => 'ok',
//                'tableau' => array()
//            ))
//            ->and($object = new testedClass())
//            ->and($object->loadSchemaFromYaml($schema))
//            ->then
//            ->exception(function() use ($object, $data) { $object->validate($data); })
//            ->hasMessage('The path "root.pas vide" cannot contain an empty value, but got "".');
//    }

    // let's check now the types
    public function testTypes()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestTypesReference.yml'))
            ->and($yaml = file_get_contents('test/data/TestTypes.yml'))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->boolean($object->validateYaml($yaml))->isEqualTo(true);
    }
    public function testTypesWrongBool()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestTypesReference.yml'))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->exception(function() use ($object) { $object->validate(array('booleen' => 'no')); })
                ->hasMessage('Invalid type for path "root.booleen". Expected boolean, but got string.');
    }
    public function testTypesWrongEnum()
    {
        $this
            ->if($schema = file_get_contents('test/data/TestTypesReference.yml'))
            ->and($object = new testedClass())
            ->and($object->loadSchemaFromYaml($schema))
            ->then
                ->exception(function() use ($object) { $object->validate(array('enume' => 'bsd')); })
                ->hasMessage('The value "bsd" is not allowed for path "root.enume". Permissible values: "windows", "mac", "linux"');
    }
}