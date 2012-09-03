<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class PartialNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        // we will validate using the partial defined in schema -> _partials -> name
        return $this->schema_validator->validatePartial($node_config[$this->schema_validator->getFullName('partial')], $data);
    }
}
