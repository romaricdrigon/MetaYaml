<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class XsdArrayNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        // always inside an xsd:element
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);

        if (! $under_root && ! (isset($node[$this->xsd_generator->getFullName('required')]) && $node[$this->xsd_generator->getFullName('required')])) {
            $writer->writeAttribute('minOccurs', '0');
        }

        // complexType
            $writer->startElementNs('xsd', 'complexType', null);
                // all : elements inside may be optional, and are unordered
                $writer->startElementNs('xsd', 'all', null);

                // TODO : not empty
                // TODO : ignore extra keys

                foreach ($node[$this->xsd_generator->getFullName('children')] as $key => $value) {
                    $this->xsd_generator->buildNode($key, $value[$this->xsd_generator->getFullName('type')], $value, $writer, false);
                }

                $writer->endElement();
            $writer->endElement();

        $writer->endElement();
    }
}
