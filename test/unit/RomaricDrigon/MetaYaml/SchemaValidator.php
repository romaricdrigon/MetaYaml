<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\SchemaValidator as testedClass;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;
use RomaricDrigon\MetaYaml\Loader\XmlLoader;

class SchemaValidator extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array('root' => array(
                '_required'=> true,
                '_content' => array(
                    'a' => array('_type' => 'text', '_strict' => true),
                    'b' => array('_type' => 'text')
            ))))
            ->then
                ->boolean($object->validate($config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate($config, 'test'); })
                    ->hasMessage('The node "root" is not an array')
                ->exception(function() use($object, $config) { $object->validate($config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage('The node "root.a" is not a text value');
    }

    public function testWithPrefix()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array(
                'root' => array(
                    'my:required'=> true,
                    'my:content' => array(
                        'a' => array('my:type' => 'text', 'my:strict' => true),
                        'b' => array('my:type' => 'text')
                    )
                ),
                'prefix' => 'my:'
            ))
            ->then
                ->boolean($object->validate($config, array('a' => 'test', 'b' => 'toto')))->isEqualTo(true)
                ->exception(function() use($object, $config) { $object->validate($config, 'test'); })
                    ->hasMessage('The node "root" is not an array')
                ->exception(function() use($object, $config) { $object->validate($config, array('a' => 10, 'b' => '5')); })
                    ->hasMessage('The node "root.a" is not a text value');
    }

    public function testPartial()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array(
                'root' => array(
                    '_required'=> true,
                    '_content' => array(
                        'a' => array('_type' => 'partial', '_partial' => 'contenu')),
                        'b' => array('_type' => 'text')
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
                    ->hasMessage('The node "contenu" is not a text value');
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
                ->boolean($object->validate($config, $data))->isEqualTo(true);
    }
    public function testTypesBase()
    {
        $this
            ->if($loader = new YamlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestTypes/TestBase.yml'))
            ->and($config = $loader->loadFromFile('test/data/TestTypes/Schema.yml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true);
    }
    public function testAttributesBase()
    {
        $this
            ->if($loader = new YamlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestAttributes/TestBase.yml'))
            ->and($config = $loader->loadFromFile('test/data/TestAttributes/Schema.yml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true);
    }
    public function testAdvancedBase()
    {
        $this
            ->if($loader = new YamlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestAdvanced/TestBase.yml'))
            ->and($config = $loader->loadFromFile('test/data/TestAdvanced/Schema.yml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true);
    }
    public function testXmlBase()
    {
        $this
            ->if($loader = new XmlLoader())
            ->and($data = $loader->loadFromFile('test/data/TestXml/TestBase.xml'))
            ->and($config = $loader->loadFromFile('test/data/TestXml/Schema.xml'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true);
    }
    public function testMetaValidation()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($json_loader = new JsonLoader())
            ->and($data = $yaml_loader->loadFromFile('test/data/TestTypes/Schema.yml'))
            ->and($config = $json_loader->loadFromFile('bin/MetaSchema.json'))
            ->and($object = new testedClass())
            ->then
                ->boolean($object->validate($config, $data))->isEqualTo(true);
    }
}