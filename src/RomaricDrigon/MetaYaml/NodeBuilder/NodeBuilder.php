<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use RomaricDrigon\MetaYaml\SchemaBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

abstract class NodeBuilder implements NodeBuilderInterface
{
    protected $schema_builder;

    public function __construct(SchemaBuilder $schema_builder)
    {
        $this->schema_builder = $schema_builder;
    }

    protected function setMetadataAttributes($metadata, NodeDefinition $nodeDef)
    {
        if (isset($metadata['_required']) && $metadata['_required']) {
            $nodeDef->isRequired();
        }

        if (isset($metadata['_not_empty']) && $metadata['_not_empty']) {
            $nodeDef->cannotBeEmpty();
        }
    }
}