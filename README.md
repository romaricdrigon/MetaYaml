# MetaYaml

A `[put your file type here]` schema validator using `[put another file type here]` files.  
At the moment, file type can be Json, Yaml, or Xml.
_The name comes from the fact that it was initially made to implement a pseudo-schema for Yaml files._

## Installation

This component is organized as a Symfony2 bundle, but actually the core has no dependencies which should prevent you to use it anywhere else.

* The core requires PHP >= 5.3.3.
* If you want to use the YamlLoader, you will need the Symfony component [Yaml](https://github.com/symfony/Yaml) (standalone component, does not require Symfony2).
* To run the tests, you'll need [atoum](https://github.com/mageekguy/atoum).

To install all these packages, the easiest is to use composer: put [composer.phar](http://getcomposer.org) in root folder, and then run `./composer.phar --update`

## Basic usage

You have to create a SchemaValidator object, and then pass it both the schema as a multidimensional php array and your data :
```php
$schema = new SchemaValidator();
$schema->validate($schema, $data);
```

You can use any of the provided loaders to obtain these arrays (yep, you can validate Xml using a schema in a Yaml file !).

Some loader examples :
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

A schema file will define the array structure (which elements are allowed, where), some attributes
(required, can be empty...) and the possible values for these elements (or their type).

Here's a simple example of a schema, using Yaml syntax :
```yaml
_root: # _root is an always required node
    _content: # array nodes have a _content, defining their children
        fleurs:
            _metadata: # each node (except _root) must have a _metadata node
                _type: array # which precises its type
                _required: true # optional, default false
            _content:
                rose:
                    _metadata:
                        _required: true
                        _type: text
                violette:
                    _metadata:
                        _type: text
                # = only rose and violette are allowed children of fleurs
```

And a valid Yaml file :
```yaml
fleurs:
    rose: une rose
    violette: une violette
```

For more examples, look inside test/data folder.

## Test

The project is extensively tested using [atoum](https://github.com/mageekguy/atoum).
To launch tests, just run in a shell `./bin/test --test-all`.

## Extending

You may want to write your own loader, using anything else.  
Take a look at any class in Loader/ folder, it's pretty simple :
you have to implement the LoaderInterface, and may want to extend Loader class (so you don't have to write loadFromFile()).
