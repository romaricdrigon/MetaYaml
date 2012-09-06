<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdNumberNodeGenerator as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdNumberNodeGenerator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
                ->variable($object->build('test', array(), $writer, false))->isNull()
                ->string($writer->outputMemory())
                ->isEqualTo('<xsd:element name="test" minOccurs="0" type="xsd:decimal"/>');
        ;
    }

    public function testNotEmpty()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
                ->variable($object->build('test', array('_not_empty' => true), $writer, false))->isNull()
                ->string($writer->outputMemory())
                ->isEqualTo('<xsd:element name="test" minOccurs="0"><xsd:simpleType><xsd:restriction base="xsd:decimal">'.
                            '<xsd:pattern value="^[^0]*$"/></xsd:restriction></xsd:simpleType></xsd:element>');
        ;
    }
}