<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

use RomaricDrigon\MetaYaml\XsdGenerator;

abstract class XsdNodeGenerator implements XsdNodeGeneratorInterface
{
    protected $xsd_generator;

    public function __construct(XsdGenerator $xsd_generator)
    {
        $this->xsd_generator = $xsd_generator;
    }

    protected function addRequired($node, \XMLWriter &$writer, $under_root) {
        if ($under_root === false // minOccurs is not allowed for first level elements
            && ! (isset($node[$this->xsd_generator->getFullName('required')])
            && $node[$this->xsd_generator->getFullName('required')])) {
            $writer->writeAttribute('minOccurs', '0');
        }
    }
}
