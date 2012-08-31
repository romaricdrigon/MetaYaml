<?php

namespace RomaricDrigon\MetaYaml\NodeValidator;

use RomaricDrigon\MetaYaml\SchemaValidator;
use RomaricDrigon\MetaYaml\Exception\NodeValidatorException;

class NodeValidatorFactory
{
    public function getValidator($name, $type, SchemaValidator $validator)
    {
        switch ($type) {
            case 'number':
                return new NumberNodeValidator($validator);
            case 'text':
                return new TextNodeValidator($validator);
            case 'boolean':
                return new BooleanNodeValidator($validator);
            case 'enum':
                return new EnumNodeValidator($validator);
            case 'array':
                return new ArrayNodeValidator($validator);
            case 'prototype':
                return new PrototypeNodeValidator($validator);
            default:
                throw new NodeValidatorException($name, 'Unknown validator type : '.$type);
        }
    }
}
