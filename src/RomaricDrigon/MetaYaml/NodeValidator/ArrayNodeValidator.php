<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class ArrayNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        if (!is_array($data)) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not an array', $name));
        }

        foreach ($node_config['_content'] as $key => $value) {
            $this->schema_validator->validateNode($name.'.'.$key, $value['_metadata']['_type'],
                $value, isset($data[$key]) ? $data[$key] : null);
        }

        return true;
    }
}
