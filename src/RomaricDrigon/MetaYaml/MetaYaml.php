<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\SchemaValidator;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;

class MetaYaml
{
    private $schema;

    // need to have a schema array
    public function __construct(array $schema, $validate = false)
    {
        $this->schema = $schema;

        if ($validate) {
            // we validate the schema using the meta schema, defining the structure of our schema
            try {
                $this->validateSchema();
            } catch (\Exception $e) {
                throw new \Exception("Unable to validate schema with error: {$e->getMessage()}");
            }
        }
    }

    // validate the schema
    // for big files (more than a few hundred lines)
    // can take up to a second
    public function validateSchema()
    {
        $meta_schema_validator = new SchemaValidator();
        $json_loader = new JsonLoader();

        // we have to check if we use a prefix
        $meta_json = file_get_contents(__DIR__.'/../../../data/MetaSchema.json');
        $prefix = isset($this->schema['prefix']) ? $this->schema['prefix'] : '_';
        $meta_json = str_replace('#', $prefix, $meta_json);

        // we validate the schema using the meta schema, defining the structure of our schema
        $meta_schema_validator->validate($json_loader->load($meta_json), $this->schema);

        return true;
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
