<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as ND;

class TextNodeBuilder extends NodeBuilder
{
    public function build($name, $node_config)
    {
        $builder = new ND();
        $child = $builder->scalarNode($name);
        $this->setMetadataAttributes($node_config['_metadata'], $child);
        
        return $child;
    }
}
