<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

class ChoiceNodeValidator extends NodeValidator
{
    public function validate($name, $node_config, $data)
    {
        if ($this->checkRequired($name, $node_config, $data)) return true;

        $valid = false;
        $messages = array();
        foreach ($node_config['_choices'] as $key => $choice_config) {
            try {
                $this->schema_validator->validateNode($name, $choice_config['_metadata']['_type'], 
                    $choice_config, $data);
                $valid = true;
                break;
            } catch (\Exception $e) {
                $messages[] = sprintf('Choice "'.$key.'" : '.$e->getMessage());
            }
        }

        if (!$valid) {
            throw new \Exception(sprintf("The node \"%s\" is invalid, some possible reasons : \n    %s",
                $name,
                implode("\n    ", $messages)));
        }

        return true;
    }
}
