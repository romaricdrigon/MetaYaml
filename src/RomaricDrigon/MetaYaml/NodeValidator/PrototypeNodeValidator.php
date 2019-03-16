<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class PrototypeNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;
        
        if (! is_array($data)) {
            throw new NodeValidatorException($name, "The node '$name' is not an array", $this->path);
        }

        // get min and max number of prototype repetition
        $min = isset($node[$this->schema_validator->getFullName('min_items')]) ? $node[$this->schema_validator->getFullName('min_items')] : 0;
        $max = isset($node[$this->schema_validator->getFullName('max_items')]) ? $node[$this->schema_validator->getFullName('max_items')] : 200;
        $n = 0;

        foreach ($data as $key => $subdata) {
            if ($n >= $max) { // because we count from 0
                throw new NodeValidatorException($name, "Prototype node '$name' has too much children", $this->path);
            }

            $this->schema_validator->validateNode($name.'.'.$key,
                $node[$this->schema_validator->getFullName('prototype')][$this->schema_validator->getFullName('type')],
                $node[$this->schema_validator->getFullName('prototype')],
                $subdata,
                $this->path . '/' . $key);

            $n++;
        }

        if ($n < $min) {
            throw new NodeValidatorException($name, "Prototype node '$name' has not enough children", $this->path);
        }

        return true;
    }
}
