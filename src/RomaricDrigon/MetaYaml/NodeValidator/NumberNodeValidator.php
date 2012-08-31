<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class NumberNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        if (!is_numeric($data)) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not numeric', 
                $name));
        }

        return true;
    }
}
