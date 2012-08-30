<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use Symfony\Component\Config\Definition\Builder\NodeBuilder as ND;

class PrototypeNodeBuilder extends NodeBuilder
{
    public function build($name, $node_config)
    {
        $node_builder = new ND();

        $child = $node_builder->arrayNode($name);

        $type = $node_config['_prototype']['_metadata']['_type'];
        //$prototype = $this->schema_builder->buildNode($type, null, $node_config['_prototype']['_content']);
        $prototype = $child->prototype($type);
        if ($type === 'array') {
            foreach ($node_config['_prototype']['_content'] as $name => $value) {
                $prototype->append($this->schema_builder->buildNode($name, $value['_metadata']['_type'], $value));
            }
        }

        $this->setMetadataAttributes($node_config['_metadata'], $child);
        if (isset($node_config['_metadata']['_ignore_extra_keys']) && $node_config['_metadata']['_ignore_extra_keys']) {
            $child->ignoreExtraKeys();
        }

        return $child;
    }
}
