<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class XsdTextNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        // always inside an xsd:element
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);
        $writer->writeAttribute('type', 'xsd:string');

        if (! $under_root && ! (isset($node[$this->xsd_generator->getFullName('required')]) && $node[$this->xsd_generator->getFullName('required')])) {
            $writer->writeAttribute('minOccurs', '0');
        }

        // TODO : strict
        // TODO : not_empty
        $writer->endElement();
    }
}
