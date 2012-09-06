<?php

namespace test\unit\RomaricDrigon\MetaYaml;

use mageekguy\atoum;
use RomaricDrigon\MetaYaml\XsdGenerator as testedClass;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;
use RomaricDrigon\MetaYaml\Loader\XmlLoader;

class XsdGenerator extends atoum\test
{
    public function testBase()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array('root' => array(
                '_type' => 'array',
                '_required'=> true,
                '_children' => array(
                    'a' => array('_type' => 'text', '_strict' => true),
                    'b' => array('_type' => 'array', '_children' => array(
                        'b0' => array('_type' => 'text'),
                        'b1' => array('_type' => 'text')
                    )),
                    'c' => array('_type' => 'text', 'required' => true),
            ))))
            ->then
                ->string($object->build($config))
                    ->isEqualTo(<<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <xsd:element name="a" type="xsd:string"/>
    <xsd:element name="b">
        <xsd:complexType>
            <xsd:all>
                <xsd:element name="b0" type="xsd:string" minOccurs="0"/>
                <xsd:element name="b1" type="xsd:string" minOccurs="0"/>
            </xsd:all>
        </xsd:complexType>
    </xsd:element>
    <xsd:element name="c" type="xsd:string"/>
</xsd:schema>

EOT
                    ) // always add a last linebreak
        ;
    }
}