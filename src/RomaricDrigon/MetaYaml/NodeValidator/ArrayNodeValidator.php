<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class ArrayNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;

        if (! is_array($data)) {
            throw new NodeValidatorException($name, "The node '$name' is not an array");
        }

        $this->checkEmpty($name, $node, $data);

        foreach ($node[$this->schema_validator->getFullName('children')] as $key => $value) {
            $this->schema_validator->validateNode($name.'.'.$key,
                $value[$this->schema_validator->getFullName('type')],
                $value,
                isset($data[$key]) ? $data[$key] : null // isset(null) is false, but no big deal
            );

            if (array_key_exists($key, $data)) {
                unset($data[$key]);
            }
        }

        if (count($data) === 0 ||
            (isset($node[$this->schema_validator->getFullName('ignore_extra_keys')])
            && $node[$this->schema_validator->getFullName('ignore_extra_keys')])) {
            return true; // we skip the next check
        }

        throw new NodeValidatorException($name,
            "The node '$name' has not allowed extra key(s): ".implode(', ', array_keys($data)));
    }

    protected function checkEmpty($name, array $node, $data) {
        if ($data === array()
            && isset($node[$this->schema_validator->getFullName('not_empty')])
            && $node[$this->schema_validator->getFullName('not_empty')]) {
            throw new NodeValidatorException($name, "The node '$name' can not be empty");
        }
    }
}
