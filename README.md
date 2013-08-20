# ParamProcessor

The ParamProcessor MediaWiki extension, formerly known as Validator, is a parameter processing
framework that provides a way to declaratively define a set of parameters and how they
should be processed. It can take such declarations together with a list of raw
parameters and provide the processed values.

[![Build Status](https://secure.travis-ci.org/wikimedia/mediawiki-extensions-Validator.png?branch=master)](http://travis-ci.org/wikimedia/mediawiki-extensions-Validator)
[![Coverage Status](https://coveralls.io/repos/wikimedia/mediawiki-extensions-Validator/badge.png?branch=master)](https://coveralls.io/r/wikimedia/mediawiki-extensions-Validator?branch=master)
[![Dependency Status](https://www.versioneye.com/php/param-processor:param-processor/dev-master/badge.png)](https://www.versioneye.com/php/param-processor:param-processor/dev-master)

On [Packagist](https://packagist.org/packages/param-processor/param-processor):
[![Latest Stable Version](https://poser.pugx.org/param-processor/param-processor/version.png)](https://packagist.org/packages/param-processor/param-processor)
[![Download count](https://poser.pugx.org/param-processor/param-processor/d/total.png)](https://packagist.org/packages/param-processor/param-processor)

## Requirements

* PHP 5.3 or later
* [DataValues](https://www.mediawiki.org/wiki/Extension:DataValues) 0.1 or later
* [ValueParsers](https://www.mediawiki.org/wiki/Extension:ValueParsers) 0.1 or later
* [ValueValidators](https://www.mediawiki.org/wiki/Extension:ValueValidators) 0.1 or later
* [ValueFormatters](https://www.mediawiki.org/wiki/Extension:ValueFormatters) 0.1 or later

## Installation

You can use [Composer](http://getcomposer.org/) to download and install
this package as well as its dependencies. Alternatively you can simply clone
the git repository and take care of loading yourself.

### Composer

To add this package as a local, per-project dependency to your project, simply add a
dependency on `param-processor/param-processor` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
Ask 1.0:

    {
        "require": {
            "param-processor/param-processor": "1.0.*"
        }
    }

### Manual

Get the ParamProcessor code, either via git, or some other means. Also get all dependencies.
You can find a list of the dependencies in the "require" section of the composer.json file.
Load all dependencies and the load the ParamProcessor library by including its entry point:
ParamProcessor.php.

## Tests

This library comes with a set up PHPUnit tests that cover all non-trivial code. You can run these
tests using the PHPUnit configuration file found in the root directory. The tests can also be run
via TravisCI, as a TravisCI configuration file is also provided in the root directory.

## Authors

ParamProcessor has been written by
[Jeroen De Dauw](https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw)
to support
[Maps](https://www.mediawiki.org/wiki/Extension:Maps)
and
[Semantic MediaWiki](https://semantic-mediawiki.org/)

## Release notes

### 1.0 (under development)



## Links

* [ParamProcessor on Packagist](https://packagist.org/packages/param-processor/param-processor)
* [ParamProcessor on Ohloh](https://www.ohloh.net/p/validator)
* [ParamProcessor on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:ParamProcessor)
* [TravisCI build status](https://travis-ci.org/wikimedia/mediawiki-extensions-Validator)
* [Latest version of the readme file](https://github.com/wikimedia/mediawiki-extensions-Validator/blob/master/README.md)
