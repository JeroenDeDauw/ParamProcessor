# ParamProcessor

ParamProcessor is a parameter processing library that provides a way to
decoratively define a set of parameters and how they should be processed.
It can take such declarations together with a list of raw parameters and
provide the processed values. For example, if one defines a parameter to
be an integer, in the range [0, 100], then ParamProcessor will verify the
input is an integer, in the specified range, and return it as an actual
integer variable.

[![Build Status](https://secure.travis-ci.org/JeroenDeDauw/ParamProcessor.png?branch=master)](http://travis-ci.org/JeroenDeDauw/ParamProcessor)
[![Code Coverage](https://scrutinizer-ci.com/g/JeroenDeDauw/ParamProcessor/badges/coverage.png?s=2ab5df62d929329584536005cdca7d2bec5501f4)](https://scrutinizer-ci.com/g/JeroenDeDauw/ParamProcessor/)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/JeroenDeDauw/ParamProcessor/badges/quality-score.png?s=c15b2cfd1c600724e0f8b754fefa8b099f90a354)](https://scrutinizer-ci.com/g/JeroenDeDauw/ParamProcessor/)

On [Packagist](https://packagist.org/packages/param-processor/param-processor):
[![Latest Stable Version](https://poser.pugx.org/param-processor/param-processor/version.png)](https://packagist.org/packages/param-processor/param-processor)
[![Download count](https://poser.pugx.org/param-processor/param-processor/d/total.png)](https://packagist.org/packages/param-processor/param-processor)

## Installation

The recommended way to use this library is via [Composer](http://getcomposer.org/).

### Composer

To add this package as a local, per-project dependency to your project, simply add a
dependency on `param-processor/param-processor` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
version 1.0 of this package:

    {
        "require": {
            "param-processor/param-processor": "1.0.*"
        }
    }

### Manual

Get the code of this package, either via git, or some other means. Also get all dependencies.
You can find a list of the dependencies in the "require" section of the composer.json file.
Then take care of autoloading the classes defined in the src directory.

## Concept

The goal of the ParamProcessor library is to make parameter handling simple and consistent.

In order to achieve this, a declarative API for defining parameters is provided. Passing in
such parameter definitions together with a list of raw input into the processor leads to
a processed list of parameters. Processing consists out of name and alias resolving, parsing,
validation, formatting and defaulting.

If ones defines an "awesomeness" parameter of type "integer", one can be sure that at the end
of the processing, there will be an integer value for the awesomeness parameter. If the user did
not provide a value, or provided something that is invalid, while the parameter it is required,
processing will abort with a fatal error. If on the other hand there is a default, the default will
be set. If the value was invalid, a warning will be kept track of. In case the user provides a valid
value, for instance "42" (string), it will be turned in the appropriate 42 (int).

## Implementation structure

Parameters are defined using the ParamProcessor\ParamDefinition class. Users can also use the array
format to define parameters and not be bound to this class. At present, it is prefered to use this
array format as the class itself is not stable yet.

Processing is done via ParamProcessor\Processor.

## Defining parameters

### Array definition schema

* <code>name</code> string, required
* <code>type</code> string enum, defulats to "string"
* <code>default</code> mixed, param will be required when null/omitted
* <code>aliases</code> array of string, defaults to empty array. Aliases for the name
* <code>trim</code> boolean, defaults to unspecified (and thus whatever the processor options are). If the value should be trimmed
* <code>islist</code> boolean, defaults to false
* <code>delimiter</code> string, defaults to ",". The delimieter between values if it is a list
* <code>manipulatedefault</code> boolea, defaults to true. If the default value should also be manipulated
* <code>values</code> array, allowed values
* <code>message</code>, string, required for now
* <code>post-format</code> callback, takes the value as only parameter and returns the new value

### Core parameter types

* boolean
* float
* integer
* string
* dimension
* coordinate

## Defining parameter types

* <code>string-parser</code> Name of a class that implements the ValueParsers\ValueParser interface
* <code>validation-callback</code> Callback that gets the raw value as only parameter and returns a boolean
* <code>validator</code> Name of a class that implements the ValueValidators\ValueValidator interface

## Examples

### Parameter definitions

```php
$paramDefintions = array();

$paramDefintions[] = array(
    'name' => 'username',
);

$paramDefintions[] = array(
    'name' => 'job',
    'default' => 'unknown',
    'values' => array( 'Developer', 'Designer', 'Manager', 'Tester' ),
);

$paramDefintions[] = array(
    'name' => 'favourite-numbers',
    'islist' => true,
    'type' => 'int',
    'default' => array(),
);
```

### Processing

```php
$inputParams = array(
    'username' => 'Jeroen',
    'job' => 'Developer',
);

$processor = ParamProcessor\Processor::newDefault();

$processor->setParameters( $inputParams, $paramDefintions );

$processingResult = $processor->processParameters();

$processedParams = $processingResult->getParameters();
```

## Tests

This library comes with a set up PHPUnit tests that cover all non-trivial code. You can run these
tests using the PHPUnit configuration file found in the root directory. The tests can also be run
via TravisCI, as a TravisCI configuration file is also provided in the root directory.

## Authors

ParamProcessor has been written by [Jeroen De Dauw](https://github.com/JeroenDeDauw) to
support the [Maps](https://github.com/JeroenDeDauw/Maps) and [Semantic MediaWiki]
(https://semantic-mediawiki.org/) projects.

## Release notes

### 1.0.1 (2013-11-29)

* Implemented ProcessingResult::hasFatal
* Added ProcessingResultTest

### 1.0 (2013-11-21)

First release as standalone PHP library.

## Links

* [DataValues Time on Packagist](https://packagist.org/packages/param-processor/param-processor)
* [DataValues Time on TravisCI](https://travis-ci.org/JeroenDeDauw/ParamProcessor)
* [MediaWiki extension "Validator"](https://www.mediawiki.org/wiki/Extension:Validator) -
a wrapper around this library for MediaWiki users
