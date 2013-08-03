<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

class XsdBooleanNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        // always inside an xsd:element
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);
        $this->addRequired($node, $writer, $under_root);
        $writer->writeAttribute('type', 'xsd:boolean');
        $writer->endElement();
    }
}
