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
ParamProcessor 1.0:

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

ParamProcessor 1.0 is currently in beta-quality and is not recommended for use in
production until the actual release.

This release is primarily a redesign of many internal APIs aimed at greater
stability and cleaner interfaces exposed to the outside.

##### Compatibility changes

* DataValues 0.1 or higher is now required
* ValueParser 0.1 or higher is now required
* ValueValidator 0.1 or higher is now required
* ValueFormatter 0.1 or higher is now required
* Changed minimum MediaWiki version from 1.16 to 1.18.
* Full compatibility with MediaWiki 1.20, 1.21, 1.22 and forward-compatibility with 1.23.
* Added compatibility with PHP 5.4.x and PHP 5.5.x
* Dropped support for Validator 0.4.x parameter definitions, including Criteria and Manipulations

### Validator 0.4.14 (2012-03-10)

* New built-in parameter type 'title'. Accepts existing and non-existing page titles which are valid within the wiki.

### Validator 0.4.13 (2011-11-30)

* ParserHook::$parser now is a reference to the original parser object, as one would suspect.
  Before this has only been the case for tag extension but not for parser function calls.

* if SFH_OBJECT_ARGS and therefore object parser function arguments are available in the MW
  version used with Validator, ParserHook::$frame will not be null anymore. Therefore a new
  function ParserHook::renderFunctionObj() is introduced, handling these SFH_OBJECT_ARGS hooks.

* ParserHook constructor now accepts a bitfield for flags to define further customization for
  registered Hooks. First option can be set via ParserHook::FH_NO_HASH to define that the function
  hook should be callable without leading hash ("{{plural:...}}"-like style).

* Option for unnamed parameter handling to work without named fallback. This allows to ignore '='
  within parameter values entirely, these parameters bust be set before any named parameter then.
  See Validator::setFunctionParams() and ParserHook::getParameterInfo() for details.

* ParserHook Validation messages will now output text in global content language instead of users interface language.

### Validator 0.4.12 (2011-10-15)

* Internationalization fix in the describe parser hook.

### Validator 0.4.11 (2011-09-14)

* Fixed compatibility fallback in Parameter::getDescription.
* Fixed handling of list parameters in ParameterInput.

### Validator 0.4.10 (2011-08-04)

* Added language parameter to describe that allows setting the lang for the generated docs.
* Added getMessage method to ParserHook class for better i18n.

### Validator 0.4.9 (2011-07-30)

* Added setMessage and getMessage methods to Parameter class for better i18n.

### Validator 0.4.8 (2011-07-19)

* Added unit tests for the criteria.
* Fixed issue with handling floats in CriterionInRange.
* Added support for open limits in CriterionHasLength and CriterionItemCount.

### Validator 0.4.7 (2011-05-15)

* Added ParameterInput class to generate HTML inputs for parameters, based on code from SMWs Special:Ask.
* Added "$manipulate = true" as second parameter for Parameter::setDefault,
  which gets passed to Parameter::setDoManipulationOfDefault.
* Boolean manipulation now ignores values that are already a boolean.

### Validator 0.4.6 (2011-03-21)

* Removed ParamManipulationBoolstr.
* Added method to get the allowed values to CriterionInArray.
* Added automatic non-using of boolean manipulation when a boolean param was defaulted to a boolean value.
* Parameter fix in ListParameter::setDefault, follow up to change in 0.4.5.

### Validator 0.4.5 (2011-03-05)

* Escaping fix in the describe parser hook.
* Added string manipulation, applied by default on strings and chars.

### Validator 0.4.4 (2011-02-16)

* Tweaks to parser usage in the ParserHook class.
* Fixed incorrect output of nested pre-tags in the describe parser hook.

### Validator 0.4.3.1 (2011-01-20)

* Removed underscore and space switching behavior for tag extensions and parser functions.

### Validator 0.4.3 (2011-01-11)

* Added describe parser hook that enables automatic documentation generation of parser hooks defined via Validator.
* Modified the ParserHook and Parameter classes to allow specifying a description message.

### Validator 0.4.2 (2010-10-28)

* Fixed compatibility with MediaWiki 1.15.x.
* Removed the lowerCaseValue field in the Parameter class and replaced it's functionality with a ParameterManipulation.

### Validator 0.4.1 (2010-10-20)

* Made several small fixes and improvements.

### Validator 0.4 (2010-10-15)

##### New features

* Added ParserHook class that allows for out-of-the-box parser function and tag extension creation
with full Validator support.
* Added listerrors parser hook that allows you to list all validation errors that occurred at the point it's rendered.
* Added support for conditional parameter adding.

##### Refactoring

Basically everything got rewritten...

* Added Parameter and ListParameter classes to replace parameter definitions in array form.
* Added ParameterCriterion and ListParameterCriterion classes for better handling of parameter criteria.
* Added ParameterManipulation and ListParameterManipulation classes for more structured formatting of parameters.
* Added ValidationError class to better describe errors.
* Replaced the error level enum by ValidationError::SEVERITY_ and ValidationError::ACTION_, which are linked in $egErrorActions. 

### Validator 0.3.6 (2010-08-26)

* Added support for 'tolower' argument in parameter info definitions.

### Validator 0.3.5 (2010-07-26)

* Fixed issue with the original parameter name (and in some cases also value) in error messages.

### Validator 0.3.4 (2010-07-07)

* Fixed issue with parameter reference that occurred in php 5.3 and later.
* Fixed escaping issue that caused parameter names in error messages to be shown incorrectly.
* Fixed small issue with parameter value trimming that caused problems when objects where passed.

### Validator 0.3.3 (2010-06-20)

* Fixed bug that caused notices when using the ValidatorManager::manageParsedParameters method in some cases.

### Validator 0.3.2 (2010-06-07)

* Added lower casing to parameter names, and optionally, but default on, lower-casing for parameter values.
* Added removal of default parameters from the default parameter queue when used as a named parameter.

### Validator 0.3.1 (2010-06-04)

* Added ValidatorManager::manageParsedParameters and Validator::setParameters.

### Validator 0.3 (2010-05-31)

* Added generic default parameter support.
* Added parameter dependency support.
* Added full meta data support for validation and formatting functions, enabling more advanced handling of parameters.
* Major refactoring to conform to MediaWiki convention.

### Validator 0.2.2 (2010-03-01)

* Fixed potential xss vectors.
* Minor code improvements.

### Validator 0.2.1 (2010-02-01)

* Changed the inclusion of the upper bound for range validation functions.
* Small language fixes.

### Validator 0.2 (2009-12-25)

* Added handling for lists of a type, instead of having list as a type. This includes per-item-validation and per-item-defaulting.
* Added list validation functions: item_count and unique_items
* Added boolean, number and char types.
* Added support for output types. The build in output types are lists, arrays, booleans and strings. Via a hook you can add your own output types.
* Added Validator_ERRORS_MINIMAL value for $egValidatorErrorLevel.
* Added warning message to ValidatorManager that will be shown for errors when egValidatorErrorLevel is Validator_ERRORS_WARN.
* Added criteria support for is_boolean, has_length and regex.

### Validator 0.1 (2009-12-17)

* Initial release, featuring parameter validation, defaulting and error generation.

## Links

* [ParamProcessor on Packagist](https://packagist.org/packages/param-processor/param-processor)
* [ParamProcessor on Ohloh](https://www.ohloh.net/p/validator)
* [ParamProcessor on MediaWiki.org](https://www.mediawiki.org/wiki/Extension:ParamProcessor)
* [TravisCI build status](https://travis-ci.org/wikimedia/mediawiki-extensions-Validator)
* [Latest version of the readme file](https://github.com/wikimedia/mediawiki-extensions-Validator/blob/master/README.md)
