<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdPatternNodeGenerator as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdPatternNodeGenerator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
                ->variable($object->build('test', array('_pattern' => '/test/'), $writer, false))->isNull()
                ->string($writer->outputMemory())
                    ->isEqualTo('<xsd:element name="test" minOccurs="0"><xsd:simpleType><xsd:restriction base="xsd:string">'.
                                '<xsd:pattern value="/test/"/></xsd:restriction></xsd:simpleType></xsd:element>');
        ;
    }
}