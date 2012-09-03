<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class PrototypeNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;
        
        if (! is_array($data)) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not an array', $name));
        }
        
        foreach ($data as $key => $subdata) {
            $this->schema_validator->validateNode($name.'.'.$key, $node_config[$this->schema_validator->getFullName('prototype')][$this->schema_validator->getFullName('type')],
                $node_config[$this->schema_validator->getFullName('prototype')], $subdata);
        }

        return true;
    }
}
