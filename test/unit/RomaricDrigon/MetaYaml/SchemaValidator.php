<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\SchemaValidator as testedClass;

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
}