<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\NodeValidator\NodeValidatorFactory;

class SchemaValidator
{
    private $factory;
    private $schema_config;

    public function __construct()
    {
        $this->factory = new NodeValidatorFactory();
    }

    public function validate($schema_config, $data)
    {
        $this->schema_config = $schema_config;

        return $this->validateNode('root', 'array', $schema_config['_root'], $data);
    }

    public function validateNode($name, $type, $node_config, $data)
    {
        $validator = $this->factory->getValidator($name, $type, $this);

        return $validator->validate($name, $node_config, $data);
    }

    public function validatePartial($name, $data)
    {
        if (! isset($this->schema_config['_partials']) || ! isset($this->schema_config['_partials'][$name]))
            throw new \Exception("You're using a partial but _partial '$name' is not defined in your schema");

        return $this->validateNode($name,
            $this->schema_config['_partials'][$name]['_metadata']['_type'],
            $this->schema_config['_partials'][$name],
            $data
        );
    }
}
