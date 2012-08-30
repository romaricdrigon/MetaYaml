<?php

namespace RomaricDrigon\MetaYaml\NodeBuilder;

interface NodeBuilderInterface
{
    public function build($name, $node_config);
}