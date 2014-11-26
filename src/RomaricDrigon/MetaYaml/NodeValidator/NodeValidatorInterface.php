<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

interface NodeValidatorInterface
{
    public function validate($name, $node, $data);
}
