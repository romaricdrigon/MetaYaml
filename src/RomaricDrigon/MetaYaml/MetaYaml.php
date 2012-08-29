<?php

namespace RomaricDrigon\MetaYaml;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

class MetaYaml
{
    protected $schema;
    protected $built_schema;

    /*
     * Public functions
     */

    // function to load our schema
    public function loadSchemaFromYaml($yaml)
    {
        $this->loadSchema(Yaml::Parse($yaml));
    }
    // load and build
    public function loadSchema(array $schema)
    {
        $this->schema = $schema;
        $builder = $this->schemaToTree($this->schema);
        $this->built_schema = $builder->buildTree();
    }

    // validate Yaml using our schema
    public function validateYaml($yaml)
    {
        return $this->validate(Yaml::Parse($yaml));
    }
    public function validate(array $data)
    {
        // check if schema is build, if possible build it
        if ($this->schema === null) {
            throw new \Exception('You should set schema, via loadSchema() or loadSchemaFromYaml, first !');
        }

        $processor = new Processor();
        $processor->process($this->built_schema, array('root' => $data));

        return true; // we could return anything!
    }

    /*
     * Private functions
     * Used to build the validation tree
     */

    private function schemaToTree(array $schema)
    {
        if (! isset($schema['_root'])) {
            throw new \Exception('Missing _root element for schema !');
        }

        // root node is specific, so treat him accordingly
        return $this->schemaRootNode($schema['_root']);
    }

    // only for the root node
    private function schemaRootNode(array $node)
    {
        $builder = new TreeBuilder();
        // there are a lot of references under the roof, so take care
        $root = $builder->root('root');

        if (! isset($node['_metadata'])) {
            throw new \Exception('Missing metadata for root node !');
        }
        if (! isset($node['_content'])) {
            throw new \Exception('Missing content for root node !');
        }
        if (! isset($node['_metadata']['_type'])) {
            throw new \Exception('Root node must have a type !');
        }

        switch ($node['_metadata']['_type']) {
            case 'array':
                $children = $root->children();

                foreach ($node['_content'] as $name => $value) {
                    $this->schemaNode($name, $children, $value);
                }

                $children->end();
                break;
        }

        return $builder;
    }

    // parsing a "normal" node
    private function schemaNode($name, NodeBuilder $builder, array $node)
    {
        if (! isset($node['_metadata'])) {
            throw new \Exception("Missing metadata for $name node !");
        }
        if (! isset($node['_metadata']['_type'])) {
            throw new \Exception("Node $name doesn't have a type !");
        }

        switch ($node['_metadata']['_type']) {
            case 'array':
                $children = $builder->arrayNode($name);
                $this->schemaNodeSetAttributes($node['_metadata'], $children);

                foreach ($node['_content'] as $name => $value) {
                    $this->schemaNode($name, $children->children(), $value);
                }

                $builder->end();
                break;
            case 'text':
                $children = $builder->scalarNode($name);
                $this->schemaNodeSetAttributes($node['_metadata'], $children);
                $builder->end();
                break;
        }
    }

    // analyze a node attributes
    private function schemaNodeSetAttributes(array $metadata, NodeDefinition $nodeDef)
    {
        if (isset($metadata['_required']) && $metadata['_required']) {
            $nodeDef->isRequired();
        }
    }
}
