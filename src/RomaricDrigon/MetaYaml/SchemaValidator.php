<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\NodeValidator\NodeValidatorFactory;

class SchemaValidator
{
    private $factory;

    public function __construct()
    {
        $this->factory = new NodeValidatorFactory();
    }

    public function validate($schema_config, $data)
    {
        return $this->validateNode('root', 'array', $schema_config['_root'], $data);
    }

    public function validateNode($name, $type, $node_config, $data)
    {
        $validator = $this->factory->getValidator($name, $type, $this);

        return $validator->validate($name, $node_config, $data);
    }
}
