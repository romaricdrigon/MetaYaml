<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class TextNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;
        if ($this->checkEmpty($name, $node_config, $data)) return true;

        $strict = isset($node_config[$this->schema_validator->getFullName('strict')]) && isset($node_config[$this->schema_validator->getFullName('strict')]);

        if (! is_scalar($data) || ($strict && ! is_string($data))) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not a text value', $name));
        }

        return true;
    }
}
