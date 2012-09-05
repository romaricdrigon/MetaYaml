<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class NumberNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;

        $strict = isset($node[$this->schema_validator->getFullName('strict')]) && isset($node[$this->schema_validator->getFullName('strict')]);

        if (! is_numeric($data) || ($strict && ! (is_integer($data) || is_float($data)))) {
            throw new NodeValidatorException($name, "The node '$name' is not a number");
        }

        return true;
    }
}
