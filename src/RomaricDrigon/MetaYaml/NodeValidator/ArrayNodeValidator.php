<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class ArrayNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;
        if ($this->checkEmpty($name, $node, $data)) return true;

        if (! is_array($data)) {
            throw new NodeValidatorException($name, "The node \"$name\" is not an array");
        }

        foreach ($node[$this->schema_validator->getFullName('children')] as $key => $value) {
            $this->schema_validator->validateNode($name.'.'.$key,
                $value[$this->schema_validator->getFullName('type')],
                $value,
                isset($data[$key]) ? $data[$key] : null
            );

            if (isset($data[$key]))
                unset($data[$key]);
        }

        // we check if we don't have extra keys in $data array, thus not allowed
        if (count($data) !== 0)
            throw new NodeValidatorException($name,
                "The node \"$name\" has not allowed extra key(s): ".implode(', ', $data)
            );

        return true;
    }
}
