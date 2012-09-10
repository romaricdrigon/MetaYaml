<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\MetaYaml as testedClass;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;

class MetaYaml extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($schema = $yaml_loader->loadFromFile('test/data/TestTypes/Schema.yml'))
            ->and($data = $yaml_loader->loadFromFile('test/data/TestTypes/TestBase.yml'))
            ->and($object = new testedClass($schema, true))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml')
                ->array($object->getSchema())->isNotNull()
                ->boolean($object->validate($data))
                    ->isEqualTo(true)
        ;
    }

    public function testPrefix()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($schema = $yaml_loader->loadFromFile('test/data/TestAdvanced/Schema.yml'))
            ->and($data = $yaml_loader->loadFromFile('test/data/TestAdvanced/TestBase.yml'))
            ->and($object = new testedClass($schema, true))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml')
                ->boolean($object->validate($data))
                    ->isEqualTo(true)
        ;
    }

    public function testRoot()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($schema = $yaml_loader->loadFromFile('test/data/TestRoot/Schema.yml'))
            ->and($data = $yaml_loader->loadFromFile('test/data/TestRoot/TestBase.yml'))
            ->and($object = new testedClass($schema, true))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml')
                ->boolean($object->validate($data))
                    ->isEqualTo(true)
        ;
    }

    public function testWrongSchema()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($very_wrong_schema = $yaml_loader->loadFromFile('test/data/TestTypes/TestBase.yml'))
            ->then
                ->exception(function() use ($very_wrong_schema) { new testedClass($very_wrong_schema, true); })
                    ->hasMessage("Unable to validate schema with error: The node 'root.root' is required")
        ;
    }

    /*
     * Tests of getDocumentation function
     */

    public function testDocumentationRoot()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($schema = $yaml_loader->loadFromFile('test/data/TestTypes/Schema.yml'))
            ->and($object = new testedClass($schema, true))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml')
                ->array($object->getDocumentationForNode())
                    ->isEqualTo(array(
                        'name' => 'root',
                        'node' => $schema['root'],
                        'prefix' => '_',
                        'partials' => $schema['partials']))
                //->boolean(print_r($object->getDocumentationForNode(array('test_choice'))))
                ->array($object->getDocumentationForNode(array('texte')))
                    ->isEqualTo(array(
                        'name' => 'texte',
                        'node' => array('_type' => 'text'),
                        'prefix' => '_',
                        'partials' => $schema['partials']))
                ->array($object->getDocumentationForNode(array('paragraph')))
                    ->isEqualTo(array(
                        'name' => 'paragraph',
                        'node' => array('_type' => 'array', '_children' => array(
                            'line_1' => array('_type' => 'text'), 'line_2' => array('_type' => 'text'))),
                        'prefix' => '_',
                        'partials' => $schema['partials']))
                ->array($object->getDocumentationForNode(array('paragraph', 'line_1')))
                    ->isEqualTo(array(
                        'name' => 'line_1',
                        'node' => array('_type' => 'text'),
                        'prefix' => '_',
                        'partials' => $schema['partials']))
                ->array($object->getDocumentationForNode(array('prototype_bool', '1')))
                    ->isEqualTo(array(
                        'name' => '1',
                        'node' => array('_type' => 'boolean'),
                        'prefix' => '_',
                        'partials' => $schema['partials']))
                ->array($object->getDocumentationForNode(array('test_choice')))
                    ->isEqualTo(array(
                        'name' => 'test_choice',
                        'node' => array(
                            '_type' => 'choice',
                            '_choices' => array(
                                1 => array('_type' => 'enum', '_values' => array('windows', 'linux')),
                                2 => array('_type' => 'number'),
                                3 => array('_type' => 'array', '_children' => array(
                                    'one_item' => array('_type' => 'number'),
                                    'another_item' => array('_type' => 'text'))))),
                        'prefix' => '_',
                        'partials' => $schema['partials']))
                ->array($object->getDocumentationForNode(array('test_choice', 'one_item')))
                    ->isEqualTo(array(
                        'name' => 'one_item',
                        'node' => array(3 => array('_type' => 'number')),
                        'prefix' => '_',
                        'partials' => $schema['partials']))
                ->exception(function() use ($object) { $object->getDocumentationForNode(array('paragraph', 'unknown')); })
                    ->hasMessage('Unable to find child unknown')
                ->exception(function() use ($object) { $object->getDocumentationForNode(array('wrong_partial')); })
                    ->hasMessage("You're using a partial but partial 'unknown' is not defined in your schema")
        ;
    }
}