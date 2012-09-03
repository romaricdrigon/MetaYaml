<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class EnumNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        $strict = isset($node_config[$this->schema_validator->getFullName('strict')]) && isset($node_config[$this->schema_validator->getFullName('strict')]);

        // because of php lousy comparaisons,
        // when strict is false, anything compared
        // to true will be ok, to false not. Let's fix this
        // by forcing them to strings
        $haystack = $node_config[$this->schema_validator->getFullName('values')];
        if (! $strict) {
            if ($data === true) {
                $data = 'true';
            }
            if ($data === false) {
                $data = 'false';
            }
            if ($key = array_search(true, $haystack, true)) {
                $haystack[$key] = 'true';
            }
            if ($key = array_search(false, $haystack, false)) {
                $haystack[$key] = 'false';
            }
        }

        if (! in_array($data, $haystack, $strict)) {
            throw new NodeValidatorException($name, 'The value "'.$data.'" is not allowed for node "'.$name.'"');
        }

        return true;
    }
}
