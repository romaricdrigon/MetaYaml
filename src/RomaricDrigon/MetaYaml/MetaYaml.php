<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\SchemaValidator;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;

class MetaYaml
{
    private $schema;

    // need to have a schema array
    public function __construct(array $schema, $validate = true)
    {
        if ($validate) {
            $meta_schema_validator = new SchemaValidator();
            $json_loader = new JsonLoader();

            // we have to check if we use a prefix
            $meta_json = file_get_contents(__DIR__.'/../../../bin/MetaSchema.json');
            $prefix = isset($schema['prefix']) ? $schema['prefix'] : '_';
            $meta_json = str_replace('#', $prefix, $meta_json);

            // we validate the schema using the meta schema, defining the structure of our schema
            $meta_schema_validator->validate($json_loader->load($meta_json), $schema);
        }

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
