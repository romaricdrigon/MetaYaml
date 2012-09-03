# MetaYaml

A `[put your file type here]` schema validator using `[put another file type here]` files.  
At the moment, file type can be Json, Yaml, or Xml.


_The name comes from the fact that it was initially made to implement a pseudo-schema for Yaml files._

## Installation

This component is organized as a Symfony2 bundle, but actually the core has no dependencies which should prevent you to use it anywhere else.

* The core requires PHP >= 5.3.3.
* If you want to use the YamlLoader, you will need the Symfony component [Yaml](https://github.com/symfony/Yaml) (standalone component, does not require Symfony2).
* To run the tests, you'll need [atoum](https://github.com/mageekguy/atoum).

To install all these packages, the easiest way is to use [composer](http://getcomposer.org): put composer.phar in root folder, and then run `./composer.phar --update`

## Basic usage

You have to create a SchemaValidator object, and then pass it both the schema and your data as multidimensional php arrays:
```php
$schema = new SchemaValidator();
$schema->validate($schema, $data);
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
$array = $loader->loadFromFile('path/to/file);
```

## How to write a schema

### Introduction

A schema file will define the array structure (which elements are allowed, where), some attributes
(required, can be empty...) and the possible values for these elements (or their type).

Here's a simple example of a schema, using Yaml syntax :
```yaml
root: # root is an always required node ; no prefix here
    _content: # array nodes have a _content, defining their children
        fleurs:
            _type: array # _type is always required
            _required: true # optional, default false
            _content:
                rose:
                    _required: true
                    _type: text
                violette:
                    _type: text
                # = only rose and violette are allowed children of fleurs
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

A schema file must have a 'root' node, which will described the first-level content.
You can optionaly define a `prefix`. By defaults it's `_` (`_type`, `_required`...).
You'll define a `partials` node if you want to use this feature.

So a basic schema file:
```yaml
root:
    # here put the elements who will be in the file
prefix: my_ # so it's gonna be my_type, my_required...
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

* `text`
* `number`
* `boolean`
* `enum`: list accepted values in _values node
* `array`: define children in a _content node ; array children must have named keys
* `prototype`: define a repetition of items whose name is not important. You must give children's type in `_prototype` node.
* `choice`: child node can be any of the nodes provided in `_choices`. Keys in `_choices` array are not important (as long as they are unique).
* `partial`: "shortcut" to a block described in `partials` root node. Provide partial name in `_partial`

For some types, you can specify additional attributes:

* `_required`: this node must always be defined (default false)
* `_not_empty` for text and array nodes: they can't be empty (respectively '' and array())
* `_strict` with text, number, boolean and enum will enforce a strict type check (respectively, with a string, an integer or a float, a boolean, any of these values). Watch out when using these with a parser which may not be type-aware (such as the Xml one; Yaml and Json should be ok)

Here's a comprehensive example:
```yaml
root:
    # root is always an array
    _content:
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
                _content:
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
partials:
    block:
        _type: array
        _content:
            line_1:
                _type: text
```

### More information

For more examples, look inside test/data folder.
In each folder, you have an .yml file and its schema.

## Test

The project is fully tested using [atoum](https://github.com/mageekguy/atoum).
To launch tests, just run in a shell `./bin/test --test-all`.

## Extending

You may want to write your own loader, using anything else.  
Take a look at any class in Loader/ folder, it's pretty simple :
you have to implement the LoaderInterface, and may want to extend Loader class (so you don't have to write `loadFromFile()`).