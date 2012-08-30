<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

use RomaricDrigon\MetaYaml\SchemaBuilder;

class NodeBuilderFactory
{
    public function getBuilder($type, SchemaBuilder $builder)
    {
        switch ($type) {
            case 'number':
                return new NumberNodeBuilder($builder);
            case 'text':
                return new TextNodeBuilder($builder);
            case 'boolean':
                return new BooleanNodeBuilder($builder);
            case 'enum':
                return new EnumNodeBuilder($builder);
            case 'array':
                return new ArrayNodeBuilder($builder);
            case 'prototype':
                return new PrototypeNodeBuilder($builder);
            default:
                throw new \Exception('Unknown node type : '.$type);
        }
    }
}
