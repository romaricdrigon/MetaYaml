# MetaYaml

A `[put your file type here]` schema validator using `[put another file type here]` files.  
At the moment, file type can be Json, Yaml, or XML.

_The name comes from the fact that it was initially made to implement a pseudo-schema for Yaml files._

## Installation

This component is organized as a Symfony2 bundle, but actually the core has no dependencies which should prevent you to use it anywhere else.

* The core requires PHP >= 5.3.3
* To use the YamlLoader, you'll need the Symfony component [Yaml](https://github.com/symfony/Yaml) (standalone component, does not require Symfony2)
* To launch the tests, you'll need [atoum](https://github.com/mageekguy/atoum)

To install all these packages, the easiest way is to use [composer](http://getcomposer.org): put composer.phar in root folder, and then run `./composer.phar --update`

## Basic usage

You have to create a MetaYaml object, and then pass it both the schema and your data as multidimensional php arrays:
```php
// create object, load schema from an array
$schema = new MetaYaml($schema);

/*
    you can optionally validate the schema
    it can take some time (up to a second for a few hundred lines)
    so do it only once, and maybe only in development!
*/
$schema->validate_schema(); // return true or throw an exception

// you could also have done this at construct
$schema = new MetaYaml($schema, true); // will load AND validate

// finally, validate your data according to the schema
$schema->validate($data); // return true or throw an exception
```

You can use any of the provided loaders to obtain these arrays (yep, you can validate Xml using a schema from an Yaml file!).

Some loader examples:
```php
// create one loader object
$loader = new JsonLoader(); // Json (will use php json_decode)
$loader = new YamlLoader(); // Yaml using Symfony Yaml component
$loader = new XmlLoader(); // Xml (using php SimpleXml)

// the usage is the same then
$array = $loader->load($some_string);
// or you can load from a file
$array = $loader->loadFromFile('path/to/file');
```

## How to write a schema

### Introduction

A schema file will define the array structure (which elements are allowed, where), some attributes (required, can be empty...) and the possible values for these elements (or their type).

Here's a simple example of a schema, using Yaml syntax :
```yaml
root: # root is always required node ; no prefix here
    _type: array # each element must always have a _type
    _children: # array nodes have a _children, defining their children
        fleurs:
            _type: array
            _required: true # optional, default false
            _children:
                rose:
                    _required: true
                    _type: text
                violette:
                    _type: text
                # -> only rose and violette are allowed children of fleurs
```

And a valid Yaml file :
```yaml
fleurs:
    rose: une rose
    violette: une violette
```

We'll continue with Yaml examples; if you're not familiar with the syntax, you may want to take a look at it's [Wikipedia page](http://en.wikipedia.org/wiki/YAML).
Of courses the same structures are possible with Json and XML, because the core is the same ; take a look at examples in test/data/ folder.

### Schema structure

A schema file must have a `root` node, which will described the first-level content.
You can optionally define a `prefix`; by defaults it's `_` (`_type`, `_required`...).
You have to define a `partials` node if you want to use this feature.

So a basic schema file:
```yaml
root:
    # here put the elements who will be in the file
    # note that root can be anything: an array, a number, a prototype...
prefix: my_ # so it's gonna be my_type, my_required, my_children...
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
* `array`: array ; define children in a _children node ; array children must have named keys ; any extra key will provoke an error
* `prototype`: define a repetition of items whose name/index is not important. You must give children's type in `_prototype` node.
* `choice`: child node can be any of the nodes provided in `_choices`. Keys in `_choices` array are not important (as long as they are unique). In each choice, it's best to put the discriminating field in first.
* `partial`: "shortcut" to a block described in `partials` root node. Provide partial name in `_partial`

You can specify additional attributes:

* general attributes:
 * `_required`: this node must always be defined (by default false)
 * `_not_empty` for text and array nodes: they can't be empty
 * `_strict` with text, number, boolean and enum will enforce a strict type check (respectively, with a string, an integer or a float, a boolean, any of these values).
 Watch out when using these with a parser which may not be type-aware (such as the XML one; Yaml and Json should be ok)
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
        texte:
            _type: text
            _not_empty: true
        enume:
            _type: enum
            _values:
                - windows
                - mac
                - linux
        entier:
            _type: number
            _strict: true
        booleen:
            _type: boolean
        prototype_array:
            _type: prototype
            _prototype:
                _type: array
                _children:
                    texte:
                        _type: text
                        _is_required: true
        paragraph:
            _type: partial
            _partial: block
        test_choice:
            _type: choice
            _choices:
                1:
                    _type: enum
                    _values:
                        - windows
                        - linux
                2:
                    _type: number
        regex:
            _type: pattern
            _pattern: /e/
partials:
    block:
        _type: array
        _children:
            line_1:
                _type: text
```

### More information

For more examples, look inside test/data folder.
In each folder, you have an .yml file and its schema. There's also a XML example.

If you're curious about an advanced usage, you can check data/MetaSchema.json: schema files are validated using this schema.

## Test

The project is fully tested using [atoum](https://github.com/mageekguy/atoum).
To launch tests, just run in a shell `./bin/test --test-all`

## Extending

You may want to write your own loader, using anything else.  
Take a look at any class in Loader/ folder, it's pretty simple :
you have to implement the LoaderInterface, and may want to extend Loader class (so you don't have to write `loadFromFile()`).

## Thanks

Thanks to [Riad Benguella](https://github.com/youknowriad) and [Julien Bianchi](https://github.com/jubianchi) for their help & advices.