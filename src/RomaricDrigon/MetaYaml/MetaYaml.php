<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\SchemaValidator;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;

class MetaYaml
{
    private $schema;

    // need to have a schema array
    public function __construct(array $schema)
    {
        $meta_schema_validator = new SchemaValidator();
        $json_loader = new JsonLoader();

        // we validate the schema using the meta schema, defining the structure of our schema
        $meta_schema_validator->validate($json_loader->loadFromFile(__DIR__.'/../../../bin/MetaSchema.json'), $schema);

        $this->schema = $schema;
    }

    // get the validated schema
    public function getSchema()
    {
        return $this->schema;
    }

    // validate some data array
    public function validate(array $data)
    {
        $data_validator = new SchemaValidator();

        return $data_validator->validate($this->schema, $data);
    }
}
