<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class ChoiceNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        $valid = false;
        $message = '';
        $count_levels = -1;
        foreach ($node_config['_choices'] as $key => $choice_config) {
            try {
                $this->schema_validator->validateNode($name, $choice_config['_metadata']['_type'], 
                    $choice_config, $data);
                $valid = true;
                break;
            } catch (NodeValidatorException $e) {
                $path = $e->getNodePath();
                $current_count_levels = count(explode('.', $e->getNodePath()));
                if ($current_count_levels > $count_levels) {
                    $message = $e->getMessage();
                    $count_levels = $current_count_levels;
                }
            }
        }

        if (!$valid) {
            throw new NodeValidatorException($name, sprintf("The node \"%s\" is invalid, we think it's because : %s",
                $name, $message));
        }

        return true;
    }
}
