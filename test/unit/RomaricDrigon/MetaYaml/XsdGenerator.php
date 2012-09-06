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
            ->and($config = array(
                'root' => array(
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
                        'e' => array('_type' => 'number'),
                        'f' => array('_type' => 'number', '_not_empty' => true),
                        'g' => array('_type' => 'boolean'),
                        'h' => array('_type' => 'enum', '_values' => array('one', '2', 3)),
                        'i' => array('_type' => 'pattern', '_pattern' => '/test/'),
                        'j' => array('_type' => 'partial', '_partial' => 'p_node'),
                        'k' => array('_type' => 'prototype', '_prototype' => array('_type' => 'text'))
                )),
                'partials' => array(
                    'p_node' => array('_type' => 'text', '_required' => true)
                )
            ))
            ->then
                //->integer(print($object->build($config)))->isEqualTo(1)
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
                <xsd:any processContents="skip" minOccurs="1" maxOccurs="unbounded"/>
            </xsd:sequence>
        </xsd:complexType>
    </xsd:element>
    <xsd:element name="e" type="xsd:decimal"/>
    <xsd:element name="f">
        <xsd:simpleType>
            <xsd:restriction base="xsd:decimal">
                <xsd:pattern value="^[^0]*$"/>
            </xsd:restriction>
        </xsd:simpleType>
    </xsd:element>
    <xsd:element name="g" type="xsd:boolean"/>
    <xsd:element name="h">
        <xsd:simpleType>
            <xsd:restriction base="xsd:string">
                <xsd:enumeration value="one"/>
                <xsd:enumeration value="2"/>
                <xsd:enumeration value="3"/>
            </xsd:restriction>
        </xsd:simpleType>
    </xsd:element>
    <xsd:element name="i">
        <xsd:simpleType>
            <xsd:restriction base="xsd:string">
                <xsd:pattern value="/test/"/>
            </xsd:restriction>
        </xsd:simpleType>
    </xsd:element>
    <xsd:element name="p_node" type="xsd:normalizedString"/>
    <xsd:element name="k">
        <xsd:complexType>
            <xsd:sequence>
                <xsd:any minOccurs="0" maxOccurs="200"/>
            </xsd:sequence>
        </xsd:complexType>
    </xsd:element>
</xsd:schema>

EOT
                    ) // always add a last linebreak
        ;
    }

    public function testWrongPartial()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array(
                'root' => array(
                    '_type' => 'array',
                    '_required'=> true,
                    '_children' => array(
                        'a' => array('_type' => 'partial', '_partial' => 'p_node')
            ))))
            ->then
                ->exception(function() use ($object, $config) { $object->build($config); })
                    ->hasMessage("You're using a partial but partial 'p_node' is not defined in your schema");
        ;
    }

    public function testWrongRootType()
    {
        $this
            ->if($object = new testedClass())
            ->and($config = array('root' => array('_type' => 'text')))
            ->then
                ->exception(function() use ($object, $config) { $object->build($config); })
                    ->hasMessage("Only array root nodes are supported");
        ;
    }
}