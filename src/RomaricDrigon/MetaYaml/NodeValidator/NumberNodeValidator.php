<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

class NumberNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        if (!is_numeric($data)) {
            throw new \Exception(sprintf('The node "%s" is not numeric', 
                $name));
        }

        return true;
    }
}
