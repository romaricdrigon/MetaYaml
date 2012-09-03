<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class ArrayNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;

        if (! is_array($data)) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not an array', $name));
        }

        foreach ($node[$this->schema_validator->getFullName('content')] as $key => $value) {
            $this->schema_validator->validateNode($name.'.'.$key, $value[$this->schema_validator->getFullName('type')],
                $value, isset($data[$key]) ? $data[$key] : null);
        }

        return true;
    }
}
