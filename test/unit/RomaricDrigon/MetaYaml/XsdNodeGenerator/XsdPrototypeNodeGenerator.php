<?php

namespace test\unit\RomaricDrigon\MetaYaml\XsdNodeGenerator;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdPrototypeNodeGenerator as testedClass;
use RomaricDrigon\MetaYaml\XsdGenerator;

class XsdPrototypeNodeGenerator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($xsd_generator = new XsdGenerator())
            ->and($object = new testedClass($xsd_generator))
            ->and($writer = new \XMLWriter())
            ->and($writer->openMemory())
            ->then
                ->variable($object->build('test', array('prototype' => array('_type' => 'text'), '_min_items' => 1, '_max_items' => 20), $writer, false))->isNull()
                ->string($writer->outputMemory())
                    ->isEqualTo('<xsd:element name="test" minOccurs="0"><xsd:complexType><xsd:sequence>'.
                                '<xsd:any minOccurs="1" maxOccurs="20"/></xsd:sequence></xsd:complexType></xsd:element>');
        ;
    }
}