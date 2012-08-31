<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

class EnumNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        if (!in_array($data, $node_config['_values'])) {
            throw new \Exception(sprintf('The value of the node "%s" is not allowed', 
                $name));
        }

        return true;
    }
}
