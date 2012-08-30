<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\NodeBuilder\NodeBuilderFactory;

class SchemaBuilder
{
    private $factory;

    public function __construct()
    {
        $this->factory = new NodeBuilderFactory();
    }

    public function build($schema_config)
    {
        $schema_config = $schema_config['_root'];

        if (! isset($schema_config['_content'])) {
            throw new \Exception('Missing _content for root node !');
        }

        $node = $this->buildNode('root', 'array', $schema_config);

        return $node->getNode(true);
    }


    public function buildNode($name, $type, $node_config)
    {
        $node_builder = $this->factory->getBuilder($type, $this);

        return $node_builder->build($name, $node_config);
    }
}
