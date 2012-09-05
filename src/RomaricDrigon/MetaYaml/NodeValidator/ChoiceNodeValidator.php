<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class ChoiceNodeValidator extends NodeValidator
{
    public function validate($name, $node, $data)
    {
        if ($this->checkRequired($name, $node, $data)) return true;

        $valid = false;
        $message = '';
        $count_levels = -1;

        foreach ($node[$this->schema_validator->getFullName('choices')] as $choice_config) {
            try {
                $this->schema_validator->validateNode($name, $choice_config[$this->schema_validator->getFullName('type')],
                    $choice_config, $data);
                $valid = true;
                break;
            } catch (NodeValidatorException $e) {
                $current_count_levels = count(explode('.', $e->getNodePath()));

                if ($current_count_levels > $count_levels) {
                    $message = $e->getMessage();
                    $count_levels = $current_count_levels;
                }
            }
        }

        if (! $valid) {
            throw new NodeValidatorException($name, "The choice node '$name' is invalid with error: $message");
        }

        return true;
    }
}
