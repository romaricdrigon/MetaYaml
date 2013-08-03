<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

class XsdArrayNodeGenerator extends XsdNodeGenerator
{
    public function build($name, $node, \XMLWriter &$writer, $under_root)
    {
        $writer->startElementNs('xsd', 'element', null);
        $writer->writeAttribute('name', $name);
        $this->addRequired($node, $writer, $under_root);

        // complexType
            $writer->startElementNs('xsd', 'complexType', null);
                // ignore_extra_keys imply a very different markup - and ignore everything!
                if ($this->addIgnoreExtraKeys($node, $writer) === false) {
                    // all : elements inside may be optional, and are unordered
                    $writer->startElementNs('xsd', 'all', null);

                    foreach ($node[$this->xsd_generator->getFullName('children')] as $key => $value) {
                        $this->xsd_generator->buildNode($key, $value[$this->xsd_generator->getFullName('type')], $value, $writer, false);
                    }

                    $writer->endElement();
                }
            $writer->endElement();

        $writer->endElement();
    }

    public function addIgnoreExtraKeys($node, \XMLWriter &$writer)
    {
        if (isset($node[$this->xsd_generator->getFullName('ignore_extra_keys')]) && $node[$this->xsd_generator->getFullName('ignore_extra_keys')]) {
            // not_empty makes sense only here - so let's test it
            $min = (isset($node[$this->xsd_generator->getFullName('not_empty')]) && $node[$this->xsd_generator->getFullName('not_empty')]) ? '1' : '0';

            $writer->startElementNs('xsd', 'sequence', null);
                $writer->startElementNs('xsd', 'any', null);
                $writer->writeAttribute('processContents', 'skip');
                $writer->writeAttribute('minOccurs', $min);
                $writer->writeAttribute('maxOccurs', 'unbounded');
                $writer->endElement();
            $writer->endElement();

            return true;
        }

        return false;
    }
}
