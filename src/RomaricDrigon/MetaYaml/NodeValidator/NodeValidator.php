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
        if (! is_null($data)) return false; // ok anyway

        if (isset($node[$this->schema_validator->getFullName('required')])
            && $node[$this->schema_validator->getFullName('required')]) {
            throw new NodeValidatorException($name, sprintf("The node '$name' is required"));
        } else {
            return true; // data null & not required, stop further validations
        }
    }

    // empty has some particularities,
    // so we have to reimplement it each time
    // protected function checkEmpty($name, array $node, $data) {}
}
