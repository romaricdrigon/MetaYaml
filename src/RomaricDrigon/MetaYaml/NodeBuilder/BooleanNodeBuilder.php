<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as ND;

class BooleanNodeBuilder extends NodeBuilder
{
    public function build($name, $node_config)
    {
        $node_builder = new ND();

        $child = $node_builder->booleanNode($name);
        $this->setMetadataAttributes($node_config['_metadata'], $child);
        
        return $child;
    }
}
