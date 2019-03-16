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
        $sub_messages = [];
        $sub_paths = [];

        foreach ($node[$this->schema_validator->getFullName('choices')] as $choice_config) {
            try {
                $this->schema_validator->validateNode($name, $choice_config[$this->schema_validator->getFullName('type')],
                    $choice_config, $data, $this->path);
                $valid = true;
                break;
            } catch (NodeValidatorException $e) {
                $current_count_levels = count(explode('.', $e->getNodePath()));

                if ($current_count_levels > $count_levels) {
                    $message = $e->getMessage();
                    $count_levels = $current_count_levels;
                }

                $sub_messages = array_merge($sub_messages, $e->getMessages());
                $sub_paths = array_merge($sub_paths, $e->getPaths());

            }
        }

        if (! $valid) {
            $messages = array_merge($sub_messages, ["The choice node '$name' is invalid with error: $message"]);
            $paths = array_merge($sub_paths, [$this->path]);
            throw new NodeValidatorException($name, $messages, $paths);
        }

        return true;
    }
}
