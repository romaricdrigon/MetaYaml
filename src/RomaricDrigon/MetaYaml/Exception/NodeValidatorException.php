<?php

namespace RomaricDrigon\MetaYaml\Exception;

class NodeValidatorException extends \Exception
{
    private $node_path;
    private $paths = [];
    private $messages = [];

  public function __construct($node_path, $messages, $paths)
    {
        $this->node_path = $node_path;
        if (!is_array($messages)) {
          $messages = [$messages];
        }
        if (!is_array($paths)) {
          $paths = [$paths];
        }
        $this->paths = $paths;
        $this->messages = $messages;

        parent::__construct(current($messages));
    }

    public function getNodePath()
    {
        return $this->node_path;
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getReport()
    {
        $candidates = [];
        $max_length = 0;
        foreach($this->paths as $index => $path) {
          $length = count(explode('/', $path));
          $candidates[$length][] = $result[] = $path . ': ' . $this->messages[$index];
          if ($length > $max_length) {
            $max_length = $length;
          }
        }
        return implode("\n", $candidates[$max_length]);
    }
}
