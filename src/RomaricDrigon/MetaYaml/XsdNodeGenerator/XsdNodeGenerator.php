<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

use RomaricDrigon\MetaYaml\XsdGenerator;

abstract class XsdNodeGenerator implements XsdNodeGeneratorInterface
{
    protected $xsd_generator;

    public function __construct(XsdGenerator $xsd_generator)
    {
        $this->xsd_generator = $xsd_generator;
    }
}