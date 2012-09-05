<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\SchemaValidator as testedClass;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;
use RomaricDrigon\MetaYaml\Loader\XmlLoader;

class SchemaValidator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array('root' => array(
                '_type' => 'array',
                '_required'=> true,
                '_children' => array(
                    'a' => array('_type' => 'text', '_strict' => true),
                    'b' => array('_type' => 'text'),
                    'c' => array('_type' => 'text', '_not_empty' => true),
            ))))
            ->then
                ->boolean($object->validate($config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->boolean($object->validate($config, array('a' => 'test', 'b' => 'test2', 'c' => 3)))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate($config, 'test'); })
                    ->hasMessage("The node 'root' is not an array")
                ->exception(function() use($object, $config) { $object->validate($config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage("The node 'root.a' is not a text value")
                ->exception(function() use($object, $config) { $object->validate($config, array('c' => '')); })
                    ->hasMessage("The node 'root.c' can not be empty")
        ;
    }

    public function testWithPrefix()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array(
                'root' => array(
                    'my:type' => 'array',
                    'my:required'=> true,
                    'my:children' => array(
                        'a' => array('my:type' => 'text', 'my:strict' => true),
                        'b' => array('my:type' => 'text')
                    )
                ),
                'prefix' => 'my:'
            ))
            ->then
                ->boolean($object->validate($config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate($config, 'test'); })
                    ->hasMessage("The node 'root' is not an array")
                ->exception(function() use($object, $config) { $object->validate($config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage("The node 'root.a' is not a text value")
        ;
    }

    public function testPartial()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array(
                'root' => array(
                    '_type' => 'array',
                    '_required'=> true,
                    '_children' => array(
                        'a' => array('_type' => 'partial', '_partial' => 'contenu'),
                        'b' => array('_type' => 'text')
                    ),
                ),
                'partials' => array(
                    'contenu' => array(
                        '_type' => 'text',
                        '_strict' => true
                    )
                )
            ))
            ->then
                ->boolean($object->validate($config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate($config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage("The node 'contenu' is not a text value")
        ;
    }

    public function testWrongPartial()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array(
            'root' => array(
                '_type' => 'array',
                '_required'=> true,
                '_children' => array(
                    'a' => array('_type' => 'partial', '_partial' => 'undefined'),
                ),
            )
        ))
            ->then
            ->exception(function() use($object, $config) { $object->validate($config, array('a' => 10)); })
                ->hasMessage("You're using a partial but partial 'undefined' is not defined in your schema")
        ;
    }

    public function testChoiceNotSatisfied()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array('root' => array(
                '_type' => 'choice',
                '_choices' => array(
                    0 => array(
                        '_type' => 'array',
                        '_children' => array('value' => array('_type' => 'enum', '_values' =>array('1')))
                    ),
                    1 => array(
                        '_type' => 'array',
                        '_children' => array('value' => array('_type' => 'enum', '_values' =>array('2')))
                    )
            ))))
            ->then
                ->boolean($object->validate($config, array('value' => 1)))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate($config, array('value' => 0)); })
                    ->hasMessage("The choice node 'root' is invalid with error: The value '0' is not allowed for node 'root.value'")
        ;
    }

    public function testRoot()
    {
        $this
            ->if($object = new testedClass())
            ->and($root_array = array('root' => array(
                '_type' => 'array',
                '_children' => array(
                    'a' => array('_type' => 'text', '_strict' => true),
                    'b' => array('_type' => 'text')
            ))))
            ->and($root_text = array('root' => array(
                    '_type' => 'text'
            )))
            ->and($root_partial = array(
                'root' => array(
                    '_type' => 'partial',
                    '_partial' => 'root_p'
                ),
                'partials' => array(
                    'root_p' => array(
                        '_type' => 'number',
                        '_strict' => true
                    )
                )
            ))
            ->and($root_prototype = array('root' => array(
                '_type' => 'prototype',
                '_prototype' => array(
                    '_type' => 'array',
                    '_children' => array('a' => array('_type' => 'text'))
            ))))
            ->and($root_choice = array('root' => array(
                '_type' => 'choice',
                '_choices' => array(
                    1 => array(
                        '_type' => 'text'
                    ),
                    2 => array(
                        '_type' => 'enum',
                        '_values' => array('ok')
                    )
                )
            )))
            ->then
                ->boolean($object->validate($root_array, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->boolean($object->validate($root_text, 'bla bla bla'))->isEqualTo(true)
                ->boolean($object->validate($root_partial, 5))->isEqualTo(true)
                ->boolean($object->validate($root_prototype, array(0 => array('a' => 'test'))))->isEqualTo(true)
                ->boolean($object->validate($root_choice, 'bla bla bla'))->isEqualTo(true)
                ->boolean($object->validate($root_choice, 'ok'))->isEqualTo(true)
        ;
    }

    /*
     * "Full" tests where we load a file
     */
    public function testBasicBase()
    {
        $this
            ->if($loader = new YamlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestBasic/TestBase.yml'))
            ->and($config = $loader->loadFromFile('test/data/TestBasic/Schema.yml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true)
        ;
    }
    public function testTypesBase()
    {
        $this
            ->if($loader = new YamlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestTypes/TestBase.yml'))
            ->and($config = $loader->loadFromFile('test/data/TestTypes/Schema.yml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true)
        ;
    }
    public function testAttributesBase()
    {
        $this
            ->if($loader = new YamlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestAttributes/TestBase.yml'))
            ->and($config = $loader->loadFromFile('test/data/TestAttributes/Schema.yml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true)
        ;
    }
    public function testAdvancedBase()
    {
        $this
            ->if($loader = new YamlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestAdvanced/TestBase.yml'))
            ->and($config = $loader->loadFromFile('test/data/TestAdvanced/Schema.yml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true)
        ;
    }
    public function testXmlBase()
    {
        $this
            ->if($loader = new XmlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestXml/TestBase.xml'))
            ->and($config = $loader->loadFromFile('test/data/TestXml/Schema.xml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true)
        ;
    }
}