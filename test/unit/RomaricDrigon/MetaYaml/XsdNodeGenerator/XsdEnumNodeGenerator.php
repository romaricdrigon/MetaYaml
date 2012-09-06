<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdEnumNodeGenerator as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdEnumNodeGenerator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
            ->variable($object->build('test', array('_values' => array('one', '2', 3)), $writer, false))->isNull()
                ->string($writer->outputMemory())
                ->isEqualTo('<xsd:element name="test" minOccurs="0"><xsd:simpleType><xsd:restriction base="xsd:string">'.
                            '<xsd:enumeration value="one"/><xsd:enumeration value="2"/><xsd:enumeration value="3"/>'.
                            '</xsd:restriction></xsd:simpleType></xsd:element>');
        ;
    }
}