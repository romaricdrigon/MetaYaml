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
        $schema_builder = new SchemaBuilder();
        $this->schema = $schema;
        $this->built_schema = $schema_builder->build($schema);
    }

    // validate Yaml using our schema
    public function validateYaml($yaml)
    {
        return $this->validate(Yaml::Parse($yaml));
    }
    public function validate(array $data)
    {
        // check if schema is build
        if ($this->schema === null) {
            throw new \Exception('You should set schema, via loadSchema() or loadSchemaFromYaml, first !');
        }

        $processor = new Processor();
        $processor->process($this->built_schema, array('root' => $data));

        return true; // we could return anything!
    }

}
