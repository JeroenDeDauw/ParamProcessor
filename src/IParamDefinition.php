<?php

namespace ParamProcessor;

use ValueParsers\ValueParser;
use ValueValidators\ValueValidator;

/**
 * Interface for parameter definition classes.
 *
 * @since 1.0
 * @deprecated since 1.0, use ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface IParamDefinition {

	/**
	 * Adds one or more aliases for the parameter name.
	 *
	 * @since 1.0
	 *
	 * @param string|string[] $aliases
	 */
	public function addAliases( $aliases );

	/**
	 * Adds one or more dependencies. There are the names of parameters
	 * that need to be validated and formatted before this one.
	 *
	 * @since 1.0
	 *
	 * @param string|string[] $dependencies
	 */
	public function addDependencies( $dependencies );

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 1.0
	 *
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 */
	public function format( IParam $param, array &$definitions, array $params );

	/**
	 * Returns the parameter name aliases.
	 *
	 * @since 1.0
	 *
	 * @return string[]
	 */
	public function getAliases(): array;

	/**
	 * Returns the default value.
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public function getDefault();

	/**
	 * Returns the delimiter to use to split the raw value in case the
	 * parameter is a list.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getDelimiter(): string;

	/**
	 * Returns a list of dependencies the parameter has, in the form of
	 * other parameter names.
	 *
	 * @since 1.0
	 *
	 * @return string[]
	 */
	public function getDependencies(): array;

	/**
	 * Returns a message that will act as a description message for the parameter.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getMessage(): string;

	/**
	 * Returns the parameters main name.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getName(): string;

	/**
	 * Returns an identifier for the type of the parameter.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getType(): string;

	/**
	 * Returns if the parameter has a certain alias.
	 *
	 * @since 1.0
	 *
	 * @param string $alias
	 *
	 * @return boolean
	 */
	public function hasAlias( string $alias ): bool;

	/**
	 * Returns if the parameter has a certain dependency.
	 *
	 * @since 1.0
	 *
	 * @param string $dependency
	 *
	 * @return boolean
	 */
	public function hasDependency( string $dependency ): bool;

	/**
	 * Returns if the parameter is a list or not.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function isList(): bool;

	/**
	 * Returns if the parameter is a required one or not.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function isRequired(): bool;

	/**
	 * Sets the default parameter value. Null indicates no default,
	 * and therefore makes the parameter required.
	 *
	 * @since 1.0
	 *
	 * @param mixed $default
	 * @param boolean $manipulate Should the default be manipulated or not? Since 0.4.6.
	 */
	public function setDefault( $default, $manipulate = true );

	/**
	 * Sets the delimiter to use to split the raw value in case the
	 * parameter is a list.
	 *
	 * @since 1.0
	 *
	 * @param $delimiter string
	 */
	public function setDelimiter( string $delimiter );

	/**
	 * Set if the parameter manipulations should be applied to the default value.
	 *
	 * @since 1.0
	 *
	 * @param boolean $manipulateDefault
	 */
	public function setDoManipulationOfDefault( bool $manipulateDefault );

	/**
	 * Sets a message for the parameter that will act as description.
	 *
	 *
	 * @since 1.0
	 *
	 * @param string $message
	 */
	public function setMessage( string $message );

	/**
	 * Returns if the parameter manipulations should be applied to the default value.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function shouldManipulateDefault(): bool;

	/**
	 * Returns a message key for a message describing the parameter type.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getTypeMessage(): string;

	/**
	 * Returns if the value should be trimmed before validation and any further processing.
	 *
	 * @since 1.0
	 *
	 * @since boolean|null
	 */
	public function trimDuringClean();

	/**
	 * Returns a ValueParser object to parse the parameters value.
	 *
	 * @since 1.0
	 *
	 * @return ValueParser
	 */
	public function getValueParser();

	/**
	 * Returns the ValueParser object to parse the parameters value.
	 *
	 * @since 1.0
	 *
	 * @param ValueParser $parser
	 */
	public function setValueParser( ValueParser $parser );

	/**
	 * Returns a ValueValidator that can be used to validate the parameters value.
	 *
	 * @since 1.0
	 *
	 * @return ValueValidator
	 */
	public function getValueValidator();

	/**
	 * Sets the ValueValidator that can be used to validate the parameters value.
	 *
	 * @since 1.0
	 *
	 * @param ValueValidator $validator
	 */
	public function setValueValidator( ValueValidator $validator );

	/**
	 * Sets a validation function that will be run before the ValueValidator.
	 *
	 * This can be used instead of a ValueValidator where validation is very
	 * trivial, ie checking if something is a boolean can be done with is_bool.
	 *
	 * @since 1.0
	 *
	 * @param callable $validationFunction
	 */
	public function setValidationCallback( callable $validationFunction );

	/**
	 * Sets the parameter definition values contained in the provided array.
	 *
	 * @since 1.0
	 *
	 * @param array $options
	 */
	public function setArrayValues( array $options );

	/**
	 * Returns a validation function that should be run before the ValueValidator.
	 *
	 * @since 1.0
	 *
	 * @return callable|null
	 */
	public function getValidationCallback();

}