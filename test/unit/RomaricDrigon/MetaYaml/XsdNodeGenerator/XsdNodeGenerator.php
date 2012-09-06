<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdNodeGenerator as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdNodeGenerator extends atoum\test
{
    public function testDefault()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new \mock\RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdNodeGenerator($xsd_generator))
            ->then
                ->object($object)->isInstanceOf('RomaricDrigon\\MetaYaml\\XsdNodeGenerator\\XsdNodeGenerator')
        ;
    }
}