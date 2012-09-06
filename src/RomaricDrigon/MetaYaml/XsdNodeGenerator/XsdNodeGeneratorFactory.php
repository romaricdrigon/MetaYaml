<?php

namespace RomaricDrigon\MetaYaml\XsdNodeGenerator;

use RomaricDrigon\MetaYaml\XsdGenerator;
use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class XsdNodeGeneratorFactory
{
    public function getGenerator($name, $type, XsdGenerator $generator)
    {
        switch ($type) {
            case 'array':
                return new XsdArrayNodeGenerator($generator);
            case 'text':
                return new XsdTextNodeGenerator($generator);
            case 'number':
                return new XsdNumberNodeGenerator($generator);
            case 'boolean':
                return new XsdBooleanNodeGenerator($generator);
            case 'enum':
                return new XsdEnumNodeGenerator($generator);
            case 'pattern':
                return new XsdPatternNodeGenerator($generator);
            case 'partial':
                return new XsdPartialNodeGenerator($generator);
            case 'prototype':
                return new XsdPrototypeNodeGenerator($generator);
            case 'choice':
                throw new NodeValidatorException($name, 'Choice nodes are not supported');
            default:
                throw new NodeValidatorException($name, 'Unknown generator type : '.$type);
        }
    }
}
