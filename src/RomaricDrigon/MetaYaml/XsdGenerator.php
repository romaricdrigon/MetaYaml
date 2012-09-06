<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\XsdNodeGenerator\XsdNodeGeneratorFactory;

class XsdGenerator
{
    private $factory;
    private $schema_config;
    private $prefix = '_';

    public function __construct()
    {
        $this->factory = new XsdNodeGeneratorFactory();
    }

    // main function

    public function build($schema_config, $indent = true)
    {
        $this->schema_config = $schema_config;

        // we're gonna create XSD (XML) using a XmlWriter
        $writer = new \XMLWriter();
        $writer->openMemory();

        if ($indent) {
            $writer->setIndent(true); // will be easier to read
            $writer->setIndentString('    '); // soft tab, 4 spaces
        }

        $writer->startDocument('1.0', 'UTF-8');
            // build writer - use a reference, we don't want to recopy it each time
            $this->buildRootNode($schema_config['root'][$this->getFullName('type')], $schema_config['root'], $writer);
        $writer->endDocument();

        return $writer->outputMemory();
    }

    // get prefix-aware name

    public function getFullName($name)
    {
        return $this->prefix . $name;
    }

    // build nodes

    public function buildRootNode($type, $node, \XMLWriter &$writer)
    {
        if ($type !== 'array') {
            throw new \Exception('Only array root nodes are supported');
        }

        $writer->startElementNs('xsd', 'schema', 'http://www.w3.org/2001/XMLSchema');

            foreach ($node[$this->getFullName('children')] as $key => $value) {
                $this->buildNode($key, $value[$this->getFullName('type')], $value, $writer, true);
            }

        $writer->endElement();
    }

    public function buildNode($name, $type, $node, \XMLWriter &$writer, $under_root = false)
    {
        $generator = $this->factory->getGenerator($name, $type, $this);

        $generator->build($name, $node, $writer, $under_root);
    }

    public function buildPartial($name, \XMLWriter &$writer, $under_root = false)
    {
        if (! isset($this->schema_config['partials']) || ! isset($this->schema_config['partials'][$name])) {
            throw new \Exception("You're using a partial but partial '$name' is not defined in your schema");
        }

        $this->buildNode($name,
            $this->schema_config['partials'][$name][$this->getFullName('type')],
            $this->schema_config['partials'][$name],
            $writer,
            $under_root
        );
    }
}
