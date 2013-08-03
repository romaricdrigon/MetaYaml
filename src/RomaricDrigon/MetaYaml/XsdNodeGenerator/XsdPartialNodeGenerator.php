<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

class XsdPartialNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        // here partial is purely a "shortcut"
        // partial node can not be required; partials yes
        // return for test code coverage...
        return $this->xsd_generator->buildPartial($node[$this->xsd_generator->getFullName('partial')], $writer, $under_root);
    }
}
