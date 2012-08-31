<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

class PrototypeNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;
        
        if (!is_array($data)) {
            throw new \Exception(sprintf('The node "%s" is not an array', 
                $name));
        }
        
        foreach ($data as $key => $subdata) {
            $this->schema_validator->validateNode($name.'.'.$key, $node_config['_prototype']['_metadata']['_type'], 
                $node_config['_prototype'], $subdata);
        }

        return true;
    }
}
