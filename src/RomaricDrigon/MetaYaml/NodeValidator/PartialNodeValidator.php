<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

class PartialNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;

        // we will validate using the partial defined in schema -> _partials -> name
        return $this->schema_validator->validatePartial($node[$this->schema_validator->getFullName('partial')], $data);
    }
}
