<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

class XsdEnumNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        // always inside an xsd:element
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);
        $this->addRequired($node, $writer, $under_root);
        // there are not way to represent a strict enum

        // simpleType with restrictions
            $writer->startElementNs('xsd', 'simpleType', null);
                $writer->startElementNs('xsd', 'restriction', null);
                $writer->writeAttribute('base', 'xsd:string');

                foreach ($node[$this->xsd_generator->getFullName('values')] as $value) {
                    $writer->startElementNs('xsd', 'enumeration', null);
                    $writer->writeAttribute('value', $value);
                    $writer->endElement();
                }

                $writer->endElement();
            $writer->endElement();

        $writer->endElement();
    }
}
