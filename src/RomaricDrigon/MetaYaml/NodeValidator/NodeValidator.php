<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\SchemaValidator;
use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

abstract class NodeValidator implements NodeValidatorInterface
{
    protected $schema_validator;

    public function __construct(SchemaValidator $schema_validator)
    {
        $this->schema_validator = $schema_validator;
    }

    protected function checkRequired($name, array $node, $data)
    {
        if (! is_null($data)) return false; // ok

        $required = isset($node[$this->schema_validator->getFullName('required')]) && $node[$this->schema_validator->getFullName('required')];

        if ($required) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is required', $name));
        } else {
            return true; // data null & not required, stop further validations
        }
    }

    protected function checkEmpty($name, array $node, $data)
    {
        if (! empty($data)) return false; // ok

        $not_empty = isset($node[$this->schema_validator->getFullName('not_empty')]) && $node[$this->schema_validator->getFullName('not_empty')];

        if ($not_empty) {
            throw new NodeValidatorException($name, sprintf('The node "%s" can not be empty', $name));
        } else {
            return true;
        }
    }
}