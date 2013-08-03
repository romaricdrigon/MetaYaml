<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

class XsdPatternNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        // always inside an xsd:element
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);
        $this->addRequired($node, $writer, $under_root);

        // simpleType with restrictions
            $writer->startElementNs('xsd', 'simpleType', null);
                $writer->startElementNs('xsd', 'restriction', null);
                $writer->writeAttribute('base', 'xsd:string');
                    $writer->startElementNs('xsd', 'pattern', null);
                    $writer->writeAttribute('value', $node[$this->xsd_generator->getFullName('pattern')]);
                    $writer->endElement();
                $writer->endElement();
            $writer->endElement();

        $writer->endElement();
    }
}
