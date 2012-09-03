<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class PrototypeNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;
        
        if (! is_array($data)) {
            throw new NodeValidatorException($name, sprintf('The node "%s" is not an array', $name));
        }
        
        foreach ($data as $key => $subdata) {
            $this->schema_validator->validateNode($name.'.'.$key, $node[$this->schema_validator->getFullName('prototype')][$this->schema_validator->getFullName('type')],
                $node[$this->schema_validator->getFullName('prototype')], $subdata);
        }

        return true;
    }
}
