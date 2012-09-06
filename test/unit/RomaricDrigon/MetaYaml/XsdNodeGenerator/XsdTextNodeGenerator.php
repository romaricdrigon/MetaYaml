<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdTextNodeGenerator as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdTextNodeGenerator extends atoum\test
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
                    ->isEqualTo('<xsd:element name="test" minOccurs="0" type="xsd:normalizedString"/>');
        ;
    }

    public function testRequired()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
                ->variable($object->build('test', array('_required' => true), $writer, false))->isNull()
                ->string($writer->outputMemory())
                    ->isEqualTo('<xsd:element name="test" type="xsd:normalizedString"/>');
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
                    ->isEqualTo('<xsd:element name="test" minOccurs="0"><xsd:simpleType><xsd:restriction base="xsd:normalizedString">'.
                                '<xsd:minLength value="200"/></xsd:restriction></xsd:simpleType></xsd:element>');
        ;
    }
}