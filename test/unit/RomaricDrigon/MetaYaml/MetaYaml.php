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

    public function testLoad()
    {
        $this
            ->if($yaml = <<<EOT
fleurs:
    roses: 5
    violettes: 15
EOT
            )
            ->and($array = array(
                'fleurs' => array(
                    'roses' => 5,
                    'violettes' => 15
                )
            ))
            ->and($object = new testedClass())
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\MetaYaml')
                ->array($object->loadSchemaFromYaml($yaml))->isEqualTo($array)
                ->array($object->loadSchema($array))->isEqualTo($array);
    }
}