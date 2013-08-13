# MetaYaml

[![Build Status](https://travis-ci.org/romaricdrigon/MetaYaml.png?branch=master)](https://travis-ci.org/romaricdrigon/MetaYaml)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b61e16d7-0756-42f1-8dff-32def155f608/mini.png)](https://insight.sensiolabs.com/projects/b61e16d7-0756-42f1-8dff-32def155f608)

A `[put your file type here]` schema validator using `[put another file type here]` files.  
At the moment, file type can be Json, Yaml, or [XML](#notes-on-xml-support). It can generate a documentation about the schema, or a XSD file (experimental).

_The name comes from the fact that it was initially made to implement a pseudo-schema for Yaml files._

> 1. [Installation](#installation)
1. [Basic usage](#basic-usage)
1. [How to write a schema](#how-to-write-a-schema)
 * [Introduction](#introduction)
 * [Schema structure](#schema-structure)
 * [Schema nodes](#schema-nodes)
 * [More information](#more-information)
1. [Documentation generator](#documentation-generator)
1. [Notes on XML support](#notes-on-xml-support)
1. [XSD generator](#xsd-generator)
1. [Test](#test)
1. [Extending](#extending)
1. [Thanks](#thanks)

## Installation

It is a standalone component:

* the core requires PHP >= 5.3.3
* to use the YamlLoader, you will need the Symfony component [Yaml](https://github.com/symfony/Yaml) (standalone component, does not require Symfony2)
* to launch the tests, you'll need [atoum](https://github.com/mageekguy/atoum)

To install all these packages, you can use [composer](http://getcomposer.org): just do `composer --update`

## Basic usage

You have to create a MetaYaml object, and then pass it both the schema and your data as multidimensional php arrays:
```php
use RomaricDrigon\MetaYaml\MetaYaml;

// create object, load schema from an array
$schema = new MetaYaml($schema);

/*
    you can optionally validate the schema
    it can take some time (up to a second for a few hundred lines)
    so do it only once, and maybe only in development!
*/
$schema->validate_schema(); // return true or throw an exception

// you could also have done this at init
$schema = new MetaYaml($schema, true); // will load AND validate the schema

// finally, validate your data array according to the schema
$schema->validate($data); // return true or throw an exception
```

You can use any of the provided loaders to obtain these arrays (yep, you can validate XML using a schema from an Yaml file!).

Some loader examples:
```php
use RomaricDrigon\MetaYaml\MetaYaml;
use RomaricDrigon\MetaYaml\Loader\YamlLoader;
use RomaricDrigon\MetaYaml\Loader\XmlLoader; // JsonLoader is already available

// create one loader object
$loader = new JsonLoader(); // Json (will use php json_decode)
$loader = new YamlLoader(); // Yaml using Symfony Yaml component
$loader = new XmlLoader(); // Xml (using php SimpleXml)

// the usage is the same then
$array = $loader->load('SOME STRING...');
// or you can load from a file
$array = $loader->loadFromFile('path/to/file');
```

## How to write a schema

### Introduction

A schema file will define the array structure (which elements are allowed, where), some attributes (required, can be empty...) and the possible values for these elements (or their type).

Here's a simple example of a schema, using Yaml syntax:
```yaml
root: # root is always required (note no prefix here)
    _type: array # each element must always have a '_type'
    _children: # array nodes have a '_children' node, defining their children
        flowers:
            _type: array
            _required: true # optional, default false
            _children:
                rose:
                    _required: true
                    _type: text
                violet:
                    _type: text
                # -> only rose and violet are allowed children of flowers
```

And a valid Yaml file :
```yaml
flowers:
    rose: "a rose"
    violet: "a violet flower"
```

We will continue with Yaml examples; if you're not familiar with the syntax, you may want to take a look at its [Wikipedia page](http://en.wikipedia.org/wiki/YAML).
Of course the same structure is possible with Json or XML, because the core is the same. Take a look at examples in `test/data/` folder.

### Schema structure

A schema file must have a `root` node, which will describe the first-level content.
You can optionally define a `prefix`; by default it is `_` (`_type`, `_required`...).

You have to define a `partials` node if you want to use this feature (learn more about it below).

A basic schema file:
```yaml
root:
    # here put the elements who will be in the file
    # note that root can have any type: an array, a number, a prototype...
prefix: my_ # so it's gonna be 'my_type', 'my_required', 'my_children'...
partials:
    block:
        # here I define a partial called block
```

### Schema nodes

Each node in the schema must have a `_type` attribute.
Here I define a node called `paragraph` whose content is some text:
```yaml
paragraph:
    _type: text
```

Those types are available:

* `text`: scalar value
* `number`: numeric value
* `boolean`: boolean value
* `pattern`: check if the value matches the regular expression provided in `_pattern`, which is a [PCRE regex](http://www.php.net/manual/en/reference.pcre.pattern.syntax.php)
* `enum`: enumeration ; list accepted values in `_values` node
* `array`: array; define children in a _children node; array's children must have determined named keys; any extra key will cause an error
* `prototype`: define a repetition of items whose name/index is not important. You must give children's type in `_prototype` node.
* `choice`: child node can be any of the nodes provided in `_choices`. Keys in `_choices` array are not important (as long as they are unique). In each choice, it's best to put the discriminating field in first.
* `partial`: "shortcut" to a block described in `partials` root node. Provide partial name in `_partial`

You can specify additional attributes:

* general attributes:
 * `_required`: this node must always be defined (by default false)
 * `_not_empty` for text, number and array nodes: they can't be empty (by default false). Respective empty values are `''`, `0` (as a string, an integer or a float), `array()`. To test for `null` values, use `_required` instead.
 * `_strict` with text, number, boolean and enum will enforce a strict type check (respectively, with a string, an integer or a float, a boolean, any of these values). Be careful when using these with a parser which may not be type-aware (such as the XML one; Yaml and json should be ok)
 * `_description`: full-text description, cf. [Documentation generator](#documentation-generator)
* only for array nodes:
 * `_ignore_extra_keys`: the node can contain children whose keys are not listed in `_children`; they'll be ignored
* only for prototype nodes:
 * `min_items`: the prototype node should contain at least 'min' elements
 * `max_items`: the opposite, the max number of elements in the prototype node (by default 200)

Here's a comprehensive example:
```yaml
root:
    _type: array
    _children:
        SomeText:
            _type: text
            _not_empty: true # so !== ''
        SomeEnum:
            _type: enum
            _values:
                - windows
                - mac
                - linux
        SomeNumber:
            _type: number
            _strict: true
        SomeBool:
            _type: boolean
        SomePrototypeArray:
            _type: prototype
            _prototype:
                _type: array
                _children:
                    SomeOtherText:
                        _type: text
                        _is_required: true # can't be null
        SomeParagraph:
            _type: partial
            _partial: aBlock # cf 'partials' below
        SomeChoice:
            _type: choice
            _choices:
                1:
                    _type: enum
                    _values:
                        - windows
                        - linux
                2:
                    _type: number
                # so our node must be either #1 or #2
        SomeRegex:
            _type: pattern
            _pattern: /e/
partials:
    aBlock:
        _type: array
        _children:
            Line1:
                _type: text
```

### More information

For more examples, look inside `test/data` folder.
In each folder, you have an .yml file and its schema. There's also a XML example.

If you're curious about an advanced usage, you can check `data/MetaSchema.json`: schema files are validated using this schema (an yep, the schema validates successfully itself!)

## Documentation generator ##

Each node can have a `_description` attribute, containing some human-readable text.
You can retrieve the documentation about a node (its type, description, other attributes...) like this:
```php
// it's recommended to validate the schema before reading documentation
$schema = new MetaYaml($schema, true);

// get documentation about root node
$schema->getDocumentationForNode();

// get documentation about a child node 'test' in an array 'a_test' under root
$schema->getDocumentationForNode(array('a_test', 'test'));

// finally, if you want to unfold (follow) all partials, set second argument to true
$schema->getDocumentationForNode(array('a_test', 'test'), true);
// watch out there's no loop inside partials!
```

It returns an associative array formatted like this:
```php
array(
    'name' => 'test', // name of current node, root for first node
    'node' => array(
        '_type' => 'array',
        '_children' => ... // and so on
    ),
    'prefix' => '_'
)
```

If the targeted node is inside a choice, the result will differ slightly:
```php
array(
    'name' => 'test', // name of current node, from the choice key in the schema
    'node' => array(
        '_is_choice' => 'true', // important: so we know next keys are choices
        0 => array(
            '_type' => 'array' // and so on, for first choice
        ),
        1 => array(
            '_type' => 'text' // and so on, for second choice
        ),
        // ...
    ),
    'prefix' => '_'
)
```
This behavior allow us to handle imbricated choices, without loosing data (you have an array level for each choice level, and you can check the flag `_is_choice`)

If you pass an invalid path (e.g. no node with the name you gave exist), it will throw an exception.

## Notes on XML support

In XML, you can store a value in a node within a child element, or using an attribute.
This is not possible in an array; the only way is to use a child.

Thus, the following conventions are enforced by the XML loader:

* elements AND attributes are stored as child, using element name and content, or attribute name and value, as respectively key and value
* if a node has an attribute and a child node with the same name, the attribute will be overwritten
* if a node has both attribute(s) and a text content, text content will be stored under key `_value`
* multiple child node with the same name will be overwritten, only the last will be retained; except if they have a `_key` attribute, which will be used thus
* namespaces are not supported
* empty nodes are skipped

Let's take an example:
```xml
<fleurs>
    <roses couleur="rose">
        <opera>une rose</opera>
        <sauvage>
            <des_bois>une autre rose</des_bois>
            <des_sous_bois sauvage="oui">encore</des_sous_bois>
        </sauvage>
    </roses>
    <tulipe>je vais disparaitre !</tulipe>
    <tulipe>deuxieme tulipe</tulipe>
    <fleur couleur="violette" sauvage="false" _key="violette">une violette</fleur>
</fleurs>
```

will give us this array:
```php
array('fleurs' =>
    'roses' => array(
        'couleur' => 'rose',
        'sauvage' => array(
            'des_bois' => 'une autre rose',
            'des_sous_bois' => array(
                'sauvage' => 'oui',
                '_value' => 'encore'
            )
        )
    ),
    'tulipe' => 'deuxieme tulipe',
    'violette' => array(
        'couleur' => 'violette',
        'sauvage' => 'false',
        '_value' => 'une violette'
    )
)
```

## XSD generator

_**Please note this feature is still experimental!**_

MetaYaml can try to generate a [XML Schema Definition](http://en.wikipedia.org/wiki/XML_Schema) from a MetaYaml schema.
You may want to use this file to pre-validate XML input, or to use in another context (client-side...).
The same conventions (cf. above) will be used.

Usage example :
```php
use RomaricDrigon\MetaYaml\MetaYaml\XsdGenerator;

// create a XsdGenerator object (requires Php XMLWriter from libxml, enabled by default)
$generator = new XsdGenerator();

// $schema is the source schema, php array
// second parameter to soft-indent generated XML (default true)
$my_xsd_string = $generator->build($schema, true);
```

A few limitations, some relative to XML Schema, apply:
* `root` node must be an `array`
* an element can't have a name beginning by a number
* all first-level nodes will be mandatory (but they may be empty)
* `choice` node are not supported
* `pattern` may have a slightly different behavior due to implementations differences
* `prototype` children nodes type will not be validated
* `strict` mode does not exists
* `ignore_extra_keys` attribute will cause all children nodes not to be validated

## Test

The project is fully tested using [atoum](https://github.com/mageekguy/atoum).
To launch tests, just run in a shell `./bin/test --test-all`

## Extending

You may want to write your own loader, using anything else.  
Take a look at any class in `Loader/ folder`, it's pretty easy: you have to implement the LoaderInterface, and may want to extend Loader class (so you don't have to write `loadFromFile()`).

## Thanks

Thanks to [Riad Benguella](https://github.com/youknowriad) and [Julien Bianchi](https://github.com/jubianchi) for their help & advice.

[Top](#metayaml)
