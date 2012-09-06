<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdNodeGeneratorFactory as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdNodeGeneratorFactory extends atoum\test
{
    public function testAll()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass())
            ->then
                ->object($object->getGenerator('test', 'text', $xsd_generator))
                ->isInstanceOf('RomaricDrigon\\MetaYaml\\XsdNodeGenerator\\XsdTextNodeGenerator')
                ->object($object->getGenerator('test', 'array', $xsd_generator))
                ->isInstanceOf('RomaricDrigon\\MetaYaml\\XsdNodeGenerator\\XsdArrayNodeGenerator')
                ->exception(function() use ($object, $xsd_generator) {
                    $object->getGenerator('test', 'random_stuff', $xsd_generator);
                })->hasMessage('Unknown generator type : random_stuff')
        ;
    }
}