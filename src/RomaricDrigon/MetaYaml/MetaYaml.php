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
        $this->schema = Yaml::Parse($yaml);
    }
    
    // validate Yaml using our schema
    public function validateYaml($yaml)
    {
        return $this->validate(Yaml::Parse($yaml));
    }

    public function validate(array $data)
    {
        if (is_null($this->schema)) {
            throw new \Exception('You should set schema, via loadSchema() or loadSchemaFromYaml, first !');
        }

        $validator = new SchemaValidator();
        $validator->validate($this->schema, $data);

        return true; // we could return anything!
    }

}
