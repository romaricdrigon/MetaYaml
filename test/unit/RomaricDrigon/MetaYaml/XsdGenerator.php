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
                    'b' => array(
                        '_type' => 'array',
                        '_children' => array(
                            'b0' => array('_type' => 'text'),
                            'b1' => array('_type' => 'text')
                    )),
                    'c' => array('_type' => 'text', '_required' => true, '_not_empty' => true),
                    'd' => array(
                        '_type' => 'array',
                        '_ignore_extra_keys' => true,
                        '_not_empty' => true,
                        '_children' => array(
                            'd0' => array('_type' => 'text'),
                            'd1' => array('_type' => 'text')
                        )),
            ))))
            ->then
                ->string($object->build($config))
                    ->isEqualTo(<<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<xsd:schema xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <xsd:element name="a" type="xsd:normalizedString"/>
    <xsd:element name="b">
        <xsd:complexType>
            <xsd:all>
                <xsd:element name="b0" minOccurs="0" type="xsd:normalizedString"/>
                <xsd:element name="b1" minOccurs="0" type="xsd:normalizedString"/>
            </xsd:all>
        </xsd:complexType>
    </xsd:element>
    <xsd:element name="c">
        <xsd:simpleType>
            <xsd:restriction base="xsd:normalizedString">
                <xsd:minLength value="200"/>
            </xsd:restriction>
        </xsd:simpleType>
    </xsd:element>
    <xsd:element name="d">
        <xsd:complexType>
            <xsd:sequence>
                <xsd:any minOccurs="1" maxOccurs="unbounded"/>
            </xsd:sequence>
        </xsd:complexType>
    </xsd:element>
</xsd:schema>

EOT
                    ) // always add a last linebreak
        ;
    }
}