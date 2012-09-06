<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdArrayNodeGenerator as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdArrayNodeGenerator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
            ->variable($object->build('test', array('_children' => array(
                'a' => array('_type' => 'text')
            ), '_required' => true), $writer, false))->isNull()
            ->string($writer->outputMemory())
            ->isEqualTo('<xsd:element name="test"><xsd:complexType><xsd:all><xsd:element name="a" minOccurs="0" type="xsd:normalizedString"/>'.
                        '</xsd:all></xsd:complexType></xsd:element>');
        ;
    }

    public function testIgnoreExtraKeys()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
            ->variable($object->build('test', array('_children' => array(
                'a' => array('_type' => 'text')
            ), '_ignore_extra_keys' => true), $writer, false))->isNull()
            ->string($writer->outputMemory())
            ->isEqualTo('<xsd:element name="test" minOccurs="0"><xsd:complexType><xsd:sequence><xsd:any processContents="skip" minOccurs="0"'.
                        ' maxOccurs="unbounded"/></xsd:sequence></xsd:complexType></xsd:element>');
        ;
    }
}