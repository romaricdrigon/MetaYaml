<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

class XsdPrototypeNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);
        $this->addRequired($node, $writer, $under_root);

        // check min and max_items attributes
        $min = isset($node[$this->xsd_generator->getFullName('min_items')]) ? $node[$this->xsd_generator->getFullName('min_items')] : '0';
        $max = isset($node[$this->xsd_generator->getFullName('max_items')]) ? $node[$this->xsd_generator->getFullName('max_items')] : '200';

        // complexType
        // we can't validate the actual type of children nodes
        $writer->startElementNs('xsd', 'complexType', null);
            $writer->startElementNs('xsd', 'sequence', null);
                $writer->startElementNs('xsd', 'any', null);
                $writer->writeAttribute('minOccurs', $min);
                $writer->writeAttribute('maxOccurs', $max);
                $writer->endElement();
            $writer->endElement();
        $writer->endElement();

        $writer->endElement();
    }
}
