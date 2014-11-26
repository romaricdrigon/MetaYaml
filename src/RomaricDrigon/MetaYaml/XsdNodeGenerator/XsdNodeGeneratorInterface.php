<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

interface XsdNodeGeneratorInterface
{
    public function build($name, $node, \XMLWriter &$writer, $under_root);
}
