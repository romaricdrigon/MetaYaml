<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class TextNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;
        $this->checkEmpty($name, $node, $data);

        if (! is_scalar($data) ||
            (isset($node[$this->schema_validator->getFullName('strict')])
            && $node[$this->schema_validator->getFullName('strict')]
            && ! is_string($data))) {
            throw new NodeValidatorException($name, "The node '$name' is not a text value");
        }

        return true;
    }

    protected function checkEmpty($name, array $node, $data) {
        if ($data === ''
            && isset($node[$this->schema_validator->getFullName('not_empty')])
            && $node[$this->schema_validator->getFullName('not_empty')]) {
            throw new NodeValidatorException($name, "The node '$name' can not be empty");
        }
    }
}
