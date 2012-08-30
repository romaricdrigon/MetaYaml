<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as ND;

class NumberNodeBuilder extends NodeBuilder
{
    public function build($name, $node_config)
    {
        $builder = new ND();
        $child = $builder->scalarNode($name);
        $this->setMetadataAttributes($node_config['_metadata'], $child);

        $child
            ->validate()
                ->ifTrue(function ($v) {return !is_numeric($v);})
                ->thenInvalid("Node $name value must be numeric")
            ->end();

        return $child;
    }
}
