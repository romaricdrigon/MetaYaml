<?php

namespace RomaricDrigon\MetaYaml\Exception;

class NodeValidatorException extends \Exception
{
    private $node_path;

    public function __construct($node_path, $message)
    {
        $this->node_path = $node_path;

        parent::__construct($message);
    }

    public function getNodePath()
    {
        return $this->node_path;
    }
}
