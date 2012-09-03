<?php

namespace test\unit\RomaricDrigon\MetaYaml\Loader;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\Loader\XmlLoader as testedClass;

class XmlLoader extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($object = new testedClass())
            ->and($xml =<<<EOT
<fleurs>
    <rose>une rose</rose>
    <violette>une violette</violette>
</fleurs>
EOT
            )
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\XmlLoader')
                ->array($object->load($xml))
                    ->isEqualTo(array(
                        'fleurs' => array(
                            'rose' => 'une rose',
                            'violette' => 'une violette'
                        )
                    ));
    }

    public function testException()
    {
        $this
            ->if($object = new testedClass())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\XmlLoader')
                ->exception(function() use ($object) { $object->load('', true); })
                    ->hasMessage('Error in XmlLoader : XML seems to be invalid');
    }

    public function testDeeper()
    {
        $this
            ->if($object = new testedClass())
            ->and($xml =<<<EOT
<fleurs>
    <roses>
        <opera>une rose</opera>
        <sauvage>
            <des_bois>une autre rose</des_bois>
        </sauvage>
    </roses>
    <violette>une violette</violette>
</fleurs>
EOT
        )
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\XmlLoader')
                ->array($object->load($xml))
                    ->isEqualTo(array(
                        'fleurs' => array(
                            'roses' => array(
                                'opera' => 'une rose',
                                'sauvage' => array (
                                    'des_bois' => 'une autre rose'
                                )
                            ),
                            'violette' => 'une violette'
                        )
                    ));
    }

    public function testWithKey()
    {
        $this
            ->if($object = new testedClass())
            ->and($xml =<<<EOT
<fleurs>
    <roses>
        <rose _key="opera">une rose</rose>
        <rose _key="sauvage">une autre rose</rose>
    </roses>
    <violette>une violette</violette>
</fleurs>
EOT
        )
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\XmlLoader')
                ->array($object->load($xml))
                    ->isEqualTo(array(
                        'fleurs' => array(
                            'roses' => array(
                                'opera' => 'une rose',
                                'sauvage' => 'une autre rose'
                            ),
                            'violette' => 'une violette'
                        )
                    ));
    }

    public function testAttributes()
    {
        $this
            ->if($object = new testedClass())
            ->and($xml =<<<EOT
<fleurs>
    <roses couleur="rose">
        <opera>une rose</opera>
        <sauvage>
            <des_bois>une autre rose</des_bois>
        </sauvage>
    </roses>
    <violette couleur="violette">une violette</violette>
</fleurs>
EOT
            )
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\XmlLoader')
                ->array($object->load($xml))
                    ->isEqualTo(array(
                        'fleurs' => array(
                            'roses' => array(
                                'couleur' => 'rose',
                                'opera' => 'une rose',
                                'sauvage' => array (
                                    'des_bois' => 'une autre rose'
                                )
                            ),
                            'violette' => array(
                                'couleur' => 'violette',
                                '_value' => 'une violette'
                            )
                        )
                    ));
    }

    public function testAdvancedFile()
    {
        $this
            ->if($object = new testedClass())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\Loader\\XmlLoader')
                ->array($object->loadFromFile('test/data/TestXml/TestBase.xml'))
                    ->isEqualTo(array (
                        'fleurs' => array(
                            'roses' => array(
                                'couleur' => 'rose',
                                'opera' => 'une rose',
                                'sauvage' => array(
                                    'des_bois' => 'une autre rose',
                                    'des_sous_bois' => array(
                                        'sauvage' => 'oui',
                                        '_value' => 'encore',
                                    ),
                                ),
                            ),
                            'tulipe' => 'deuxieme tulipe',
                            'violette' => array(
                                'couleur' => 'violette',
                                'sauvage' => 'false',
                                '_value' => 'une violette',
                            ),
                        ),
                    ));
    }
}