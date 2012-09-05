<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class BooleanNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;

        $strict = isset($node[$this->schema_validator->getFullName('strict')]) && isset($node[$this->schema_validator->getFullName('strict')]);

        if (is_bool($data) || (! $strict && ($data == 'true' || $data == 'false'))) {
            return true;
        } else {
            throw new NodeValidatorException($name, "The node '$name' is not a boolean");
        }
    }
}
