<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class BooleanNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        if (!is_bool($data)) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not a boolean', 
                $name));
        }

        return true;
    }
}
