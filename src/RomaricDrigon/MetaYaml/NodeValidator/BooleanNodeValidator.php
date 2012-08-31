<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class BooleanNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        $strict = isset($node_config['_metadata']['_strict']) && isset($node_config['_metadata']['_strict']);

        if (is_bool($data) || (! $strict && ($data == 'true' || $data == 'false'))) {
            return true;
        } else {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not a boolean', 
                $name));
        }
    }
}
