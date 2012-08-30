<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as ND;

class ArrayNodeBuilder extends NodeBuilder
{
    public function build($name, $node_config)
    {
        $node_builder = new ND();

        $child = $node_builder->arrayNode($name);

        if (isset($node_config['_metadata'])) {   
            $this->setMetadataAttributes($node_config['_metadata'], $child);
            if (isset($node_config['_metadata']['_ignore_extra_keys']) && $node_config['_metadata']['_ignore_extra_keys']) {
                $child->ignoreExtraKeys();
            }
        }

        foreach ($node_config['_content'] as $name => $value) {
            $child->append($this->schema_builder->buildNode($name, $value['_metadata']['_type'], $value));
        }

        return $child;
    }
}
