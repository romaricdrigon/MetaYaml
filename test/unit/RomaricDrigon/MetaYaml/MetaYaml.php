<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\MetaYaml as testedClass;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;

class MetaYaml extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($schema = $yaml_loader->loadFromFile('test/data/TestTypes/Schema.yml'))
            ->and($data = $yaml_loader->loadFromFile('test/data/TestTypes/TestBase.yml'))
            ->and($object = new testedClass($schema))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml')
                ->array($object->getSchema())->isEqualTo(
                    array (
                        'root' =>
                        array (
                            '_type' => 'array',
                            '_content' =>
                            array (
                                'texte' =>
                                array (
                                    '_type' => 'text',
                                ),
                                'multi_ligne' =>
                                array (
                                    '_type' => 'text',
                                ),
                                'enume' =>
                                array (
                                    '_type' => 'enum',
                                    '_values' =>
                                    array (
                                        0 => 'windows',
                                        1 => 'linux',
                                    ),
                                ),
                                'nombre' =>
                                array (
                                    '_type' => 'number',
                                ),
                                'booleen' =>
                                array (
                                    '_type' => 'boolean',
                                ),
                                'test_choice' =>
                                array (
                                    '_type' => 'choice',
                                    '_choices' =>
                                    array (
                                        1 =>
                                        array (
                                            '_type' => 'enum',
                                            '_values' =>
                                            array (
                                                0 => 'windows',
                                                1 => 'linux',
                                            ),
                                        ),
                                        2 =>
                                        array (
                                            '_type' => 'number',
                                        ),
                                    ),
                                ),
                                'prototype_bool' =>
                                array (
                                    '_type' => 'prototype',
                                    '_prototype' =>
                                    array (
                                        '_type' => 'boolean',
                                    ),
                                ),
                                'prototype_array' =>
                                array (
                                    '_type' => 'prototype',
                                    '_prototype' =>
                                    array (
                                        '_type' => 'array',
                                        '_content' =>
                                        array (
                                            'texte' =>
                                            array (
                                                '_type' => 'text',
                                                '_is_required' => true,
                                            ),
                                        ),
                                    ),
                                ),
                                'paragraph' =>
                                array (
                                    '_type' => 'partial',
                                    '_partial' => 'block',
                                ),
                            ),
                        ),
                        'partials' =>
                        array (
                            'block' =>
                            array (
                                '_type' => 'array',
                                '_content' =>
                                array (
                                    'line_1' =>
                                    array (
                                        '_type' => 'text',
                                    ),
                                    'line_2' =>
                                    array (
                                        '_type' => 'text',
                                    ),
                                ),
                            ),
                        ),
                    )
                    )
                ->boolean($object->validate($data))
                    ->isEqualTo(true);
    }

    public function testPrefix()
    {
        $this
            ->if($yaml_loader = new YamlLoader())
            ->and($schema = $yaml_loader->loadFromFile('test/data/TestAdvanced/Schema.yml'))
            ->and($data = $yaml_loader->loadFromFile('test/data/TestAdvanced/TestBase.yml'))
            ->and($object = new testedClass($schema))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml')
                ->boolean($object->validate($data))
                    ->isEqualTo(true);
    }
}