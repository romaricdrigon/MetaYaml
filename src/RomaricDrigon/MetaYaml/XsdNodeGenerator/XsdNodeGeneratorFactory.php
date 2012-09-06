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
            /*case 'number':
                return new NumberNodeValidator($validator);
            case 'pattern':
                return new PatternNodeValidator($validator);
            case 'boolean':
                return new BooleanNodeValidator($validator);
            case 'enum':
                return new EnumNodeValidator($validator);
            case 'prototype':
                return new PrototypeNodeValidator($validator);
            case 'choice':
                return new ChoiceNodeValidator($validator);
            case 'partial':
                return new PartialNodeValidator($validator);*/
            default:
                throw new NodeValidatorException($name, 'Unknown generator type : '.$type);
        }
    }
}
