<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as ND;

class EnumNodeBuilder extends NodeBuilder
{
    public function build($name, $node_config)
    {
        $node_builder = new ND();

        $child = $node_builder->enumNode($name);
        $this->setMetadataAttributes($node_config['_metadata'], $child);
        $child->values($node_config['_values']);

        return $child;
    }
}
