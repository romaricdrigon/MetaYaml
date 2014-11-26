<?php

namespace RomaricDrigon\MetaYaml;

use RomaricDrigon\MetaYaml\SchemaValidator;
use RomaricDrigon\MetaYaml\Loader\JsonLoader;

class MetaYaml
{
    private $schema;
    private $prefix = '_';

    // need to have a schema array
    public function __construct(array $schema, $validate = false)
    {
        $this->schema = $schema;

        if (isset($this->schema['prefix'])) {
            $this->prefix = $this->schema['prefix'];
        }

        if ($validate) {
            // we validate the schema using the meta schema, defining the structure of our schema
            try {
                $this->validateSchema();
            } catch (\Exception $e) {
                throw new \Exception("Unable to validate schema with error: {$e->getMessage()}");
            }
        }
    }

    // validate the schema
    // for big files (more than a few hundred lines)
    // can take up to a second
    public function validateSchema()
    {
        $meta_schema_validator = new SchemaValidator();
        $json_loader = new JsonLoader();

        // we have to check if we use a prefix
        $meta_json = file_get_contents(__DIR__.'/../../../data/MetaSchema.json');
        $meta_json = str_replace('#', $this->prefix, $meta_json);

        // we validate the schema using the meta schema, defining the structure of our schema
        $meta_schema_validator->validate($json_loader->load($meta_json), $this->schema);

        return true;
    }

    // get the validated schema
    public function getSchema()
    {
        return $this->schema;
    }

    // validate some data array
    public function validate(array $data)
    {
        $data_validator = new SchemaValidator();

        return $data_validator->validate($this->schema, $data);
    }

    // get the documentation
    public function getDocumentationForNode(array $keys = array(), $unfold_all = false)
    {
        $node = $this->findNode($this->schema['root'], $keys, $unfold_all);

        return array(
            'name' => end($keys) ?: 'root',
            'node' =>  $node,
            'prefix' => $this->prefix
        );
    }
    private function findNode(array $array, array $keys, $unfold_all)
    {
        // first, if it's a partial, let's naviguate
        if (isset($array[$this->prefix.'type']) && $array[$this->prefix.'type'] === 'partial') {
            $p_name = $array[$this->prefix.'partial'];

            if (! isset($this->schema['partials']) || ! isset($this->schema['partials'][$p_name])) {
                throw new \Exception("You're using a partial but partial '$p_name' is not defined in your schema");
            }

            return $this->findNode($this->schema['partials'][$p_name], $keys, $unfold_all);
        }

        // we're on target, return the result
        if ($keys === array()) {
            // on more thing: dig one more level of partial
            return $this->unfoldPartials($array, $unfold_all);
        }

        // they're still some keys, dig deeper
        if (isset($array[$this->prefix.'type'])) {
            switch ($array[$this->prefix.'type']) {
                case 'prototype': //we have to ignore one key
                    array_shift($keys);
                    return $this->findNode($array[$this->prefix.'prototype'], $keys, $unfold_all);
                case 'array': // let's check the children
                    if (isset($array[$this->prefix.'children'][$keys[0]])) {
                        $child = $array[$this->prefix.'children'][$keys[0]];
                        array_shift($keys);
                        return $this->findNode($child, $keys, $unfold_all);
                    }
                    break;
                case 'choice': // choice, return an array of possibilities
                    $choices = array();
                    foreach ($array[$this->prefix.'choices'] as $name => $choice) {
                        try {
                            $choices[$name] = $this->findNode($choice, $keys, $unfold_all);
                        } catch (\Exception $e) {} // exception = invalid choice, so skip it
                    }
                    return $choices + array($this->prefix.'is_choice' => 'true');
            }
        }

        throw new \Exception("Unable to find child {$keys[0]}");
    }
    private function unfoldPartials(array $node, $unfold_all, $n = 0)
    {
        if ($n > 20) {
            throw new \Exception("Partial loop detected while using unfold_partial option");
        }

        // first, if it's a partial, let's naviguate
        if (isset($node[$this->prefix.'type']) && $node[$this->prefix.'type'] === 'partial') {
            return $this->unfoldPartials($this->schema['partials'][$node[$this->prefix.'partial']], $unfold_all, $n+1);
        }

        if (isset($node[$this->prefix.'children'])) {
            foreach ($node[$this->prefix.'children'] as &$child) {
                if ($child[$this->prefix.'type'] === 'partial') {
                    $child = $this->schema['partials'][$child[$this->prefix.'partial']];
                }

                if ($unfold_all === true) {
                    $child = $this->unfoldPartials($child, $unfold_all, $n+1);
                }
            }
        }
        if (isset($node[$this->prefix.'prototype'])) {
            if ($node[$this->prefix.'prototype'][$this->prefix.'type'] === 'partial') {
                $node[$this->prefix.'prototype'] = $this->schema['partials'][$node[$this->prefix.'prototype'][$this->prefix.'partial']];
            }

            if ($unfold_all === true) {
                $node[$this->prefix.'prototype'] = $this->unfoldPartials($node[$this->prefix.'prototype'], $unfold_all, $n+1);
            }
        }
        if (isset($node[$this->prefix.'choices'])) {
            foreach ($node[$this->prefix.'choices'] as &$child) {
                if ($child[$this->prefix.'type'] === 'partial') {
                    $child = $this->schema['partials'][$child[$this->prefix.'partial']];
                }

                if ($unfold_all === true) {
                    $child = $this->unfoldPartials($child, $unfold_all, $n+1);
                }
            }
        }

        return $node;
    }
}
