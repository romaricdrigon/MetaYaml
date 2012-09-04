<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\NodeValidator\NodeValidatorFactory;

class SchemaValidator
{
    private $factory;
    private $schema_config;
    private $prefix = '_';

    public function __construct()
    {
        $this->factory = new NodeValidatorFactory();
    }

    // main function

    public function validate($schema_config, $data)
    {
        $this->schema_config = $schema_config;

        if (isset($schema_config['prefix'])) {
            $this->prefix = $schema_config['prefix'];
        }

        return $this->validateNode('root',
            $schema_config['root'][$this->getFullName('type')],
            $schema_config['root'],
            $data
        );
    }

    // get prefix-aware name

    public function getFullName($name)
    {
        return $this->prefix . $name;
    }

    // validate nodes

    public function validateNode($name, $type, $node, $data)
    {
        $validator = $this->factory->getValidator($name, $type, $this);

        return $validator->validate($name, $node, $data);
    }

    public function validatePartial($name, $data)
    {
        if (! isset($this->schema_config['partials']) || ! isset($this->schema_config['partials'][$name])) {
            throw new \Exception("You're using a partial but partial '$name' is not defined in your schema");
        }

        return $this->validateNode($name,
            $this->schema_config['partials'][$name][$this->getFullName('type')],
            $this->schema_config['partials'][$name],
            $data
        );
    }
}
