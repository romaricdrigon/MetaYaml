<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

class TextNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;
        if ($this->checkEmpty($name, $node_config, $data)) return true;

        if (!is_string($data)) {
            throw new \Exception(sprintf('The node "%s" is not a string', 
                $name));
        }

        return true;
    }
}
