<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\SchemaValidator;

abstract class NodeValidator implements NodeValidatorInterface
{
    protected $schema_validator;

    public function __construct(SchemaValidator $schema_validator)
    {
        $this->schema_validator = $schema_validator;
    }

    protected function checkRequired($name, $node_config, $data)
    {
        $required = (!isset($node_config['_metadata']['_required']) || $node_config['_metadata']['_required']);
        if ($required && is_null($data)) {
            throw new \Exception(sprintf('The node "%s" is required', $name));
        } elseif (!$required && is_null($data)) {
            return true;
        }

        return false;
    }

    protected function checkEmpty($name, $node_config, $data)
    {
        $not_empty = (!isset($node_config['_metadata']['_not_empty']) || $node_config['_metadata']['_not_empty']);
        if ($not_empty && empty($data)) {
            throw new \Exception(sprintf('The node "%s" can not be empty', $name));
        } elseif (!$not_empty && empty($data)) {
            return true;
        }

        return false;
    }
}