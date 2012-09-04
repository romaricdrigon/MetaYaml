<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class PatternNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;

        // preg_match return false if there's an error, 0 if not found (== false), so lousy comparaison is good
        if (preg_match($node[$this->schema_validator->getFullName('pattern')], $data) == false) {
            throw new NodeValidatorException($name,
                "Node '$name' does not match pattern '{$node[$this->schema_validator->getFullName('pattern')]}'"
            );
        }

        return true;
    }
}
