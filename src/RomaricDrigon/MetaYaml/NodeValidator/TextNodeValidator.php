<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class TextNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;
        if ($this->checkEmpty($name, $node, $data)) return true;

        $strict = isset($node[$this->schema_validator->getFullName('strict')]) && isset($node[$this->schema_validator->getFullName('strict')]);

        if (! is_scalar($data) || ($strict && ! is_string($data))) {
            throw new NodeValidatorException($name, "The node '$name' is not a text value");
        }

        return true;
    }
}
