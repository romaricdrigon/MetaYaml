<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

class XsdNumberNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        // always inside an xsd:element
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);
        $this->addRequired($node, $writer, $under_root);
        // there are not way to represent a strict string

        if ($this->addNotEmpty($node, $writer) === false) {
            $writer->writeAttribute('type', 'xsd:decimal');
        }

        $writer->endElement();
    }

    public function addNotEmpty($node, \XMLWriter &$writer)
    {
        if (isset($node[$this->xsd_generator->getFullName('not_empty')]) && $node[$this->xsd_generator->getFullName('not_empty')]) {
            $writer->startElementNs('xsd', 'simpleType', null);
                $writer->startElementNs('xsd', 'restriction', null);
                $writer->writeAttribute('base', 'xsd:decimal');
                    $writer->startElementNs('xsd', 'pattern', null);
                    $writer->writeAttribute('value', '^[^0]*$');
                    $writer->endElement();
                $writer->endElement();
            $writer->endElement();

            return true;
        }

        return false;
    }
}
