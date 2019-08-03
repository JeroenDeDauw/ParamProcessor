<?php

namespace ParamProcessor;

use Exception;
use ParamProcessor\PackagePrivate\Param;
use ValueParsers\NullParser;
use ValueParsers\ValueParser;
use ValueValidators\NullValidator;
use ValueValidators\ValueValidator;

/**
 * Specifies what kind of values are accepted, how they should be validated,
 * how they should be formatted, what their dependencies are and how they should be described.
 *
 * Try to avoid using this interface outside of ParamProcessor for anything else than defining parameters.
 * In particular, do not derive from this class to implement methods such as formatValue.
 *
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
/* final */ class ParamDefinition implements IParamDefinition {

	/**
	 * Indicates whether parameters that are provided more then once  should be accepted,
	 * and use the first provided value, or not, and generate an error.
	 *
	 * @deprected since 1.7
	 *
	 * @var boolean
	 */
	public static $acceptOverriding = false;

	/**
	 * Indicates whether parameters not found in the criteria list
	 * should be stored in case they are not accepted. The default is false.
	 *
	 * @deprected since 1.7
	 *
	 * @var boolean
	 */
	public static $accumulateParameterErrors = false;

	protected $type;
	protected $name;
	protected $default;
	protected $isList;

	/**
	 * A message that acts as description for the parameter or false when there is none.
	 * Can be obtained via getMessage and set via setMessage.
	 *
	 * @var string
	 */
	protected $message;

	/**
	 * Indicates if the parameter value should trimmed during the clean process.
	 * @var boolean|null
	 */
	protected $trimValue = null;

	/**
	 * Indicates if the parameter manipulations should be applied to the default value.
	 * @var boolean
	 */
	protected $applyManipulationsToDefault = true;

	/**
	 * Dependency list containing parameters that need to be handled before this one.
	 * @var string[]
	 */
	protected $dependencies = [];

	/**
	 * @var string
	 */
	protected $delimiter = ',';

	/**
	 * List of aliases for the parameter name.
	 *
	 * @var string[]
	 */
	protected $aliases = [];

	/**
	 * Original array definition of the parameter
	 * @var array
	 */
	protected $options = [];

	/**
	 * @var ValueParser|null
	 */
	protected $parser = null;

	/**
	 * @var ValueValidator|null
	 */
	protected $validator = null;

	/**
	 * @var callable|null
	 */
	protected $validationFunction = null;

	/**
	 * @param string $type
	 * @param string $name
	 * @param mixed $default Use null for no default (which makes the parameter required)
	 * @param string $message
	 * @param boolean $isList
	 */
	public function __construct( string $type, string $name, $default = null, string $message = null, bool $isList = false ) {
		$this->type = $type;
		$this->name = $name;
		$this->default = $default;
		$this->message = $message ?? 'validator-message-nodesc';
		$this->isList = $isList;

		$this->postConstruct();
	}

	/**
	 * Allows deriving classed to do additional stuff on instance construction
	 * without having to get and pass all the constructor arguments.
	 *
	 * @since 1.0
	 */
	protected function postConstruct() {

	}

	/**
	 * Returns if the value should be trimmed before validation and any further processing.
	 * - true: always trim
	 * - false: never trim
	 * - null: trim based on context settings
	 */
	public function trimDuringClean(): ?bool {
		return $this->trimValue;
	}

	/**
	 * Returns the parameter name aliases.
	 * @return string[]
	 */
	public function getAliases(): array {
		return $this->aliases;
	}

	public function hasAlias( string $alias ): bool {
		return in_array( $alias, $this->getAliases() );
	}

	/**
	 * @deprecated since 1.7
	 * Returns if the parameter has a certain dependency.
	 */
	public function hasDependency( string $dependency ): bool {
		return in_array( $dependency, $this->getDependencies() );
	}

	/**
	 * Returns the list of allowed values, or an empty array if there is no such restriction.
	 */
	public function getAllowedValues(): array {
		$allowedValues = [];

		if ( $this->validator !== null && method_exists( $this->validator, 'getWhitelistedValues' ) ) {
			if ( method_exists( $this->validator, 'setOptions' ) ) {
				$this->validator->setOptions( $this->options );
			}

			$allowedValues = $this->validator->getWhitelistedValues();

			if ( $allowedValues === false ) {
				$allowedValues = [];
			}
		}

		return $allowedValues;
	}

	/**
	 * @deprecated since 1.7
	 *
	 * @param mixed $default
	 * @param boolean $manipulate Should the default be manipulated or not? Since 0.4.6.
	 */
	public function setDefault( $default, $manipulate = true ) {
		$this->default = $default;
		$this->setDoManipulationOfDefault( $manipulate );
	}

	/**
	 * Returns the default value.
	 * @return mixed
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * Returns a message describing the parameter.
	 */
	public function getMessage(): string {
		return $this->message;
	}

	/**
	 * This should be a message key, ie something that can be passed
	 * to wfMsg. Not an actual text.
	 */
	public function setMessage( string $message ) {
		$this->message = $message;
	}

	/**
	 * Set if the parameter manipulations should be applied to the default value.
	 */
	public function setDoManipulationOfDefault( bool $doOrDoNotThereIsNoTry ) {
		$this->applyManipulationsToDefault = $doOrDoNotThereIsNoTry;
	}

	public function shouldManipulateDefault(): bool {
		return $this->applyManipulationsToDefault;
	}

	/**
	 * Adds one or more aliases for the parameter name.
	 *
	 * @param string|string[] $aliases
	 */
	public function addAliases( $aliases ) {
		$args = func_get_args();
		$this->aliases = array_merge( $this->aliases, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * Adds one or more dependencies. There are the names of parameters
	 * that need to be validated and formatted before this one.
	 *
	 * @param string|string[] $dependencies
	 */
	public function addDependencies( $dependencies ) {
		$args = func_get_args();
		$this->dependencies = array_merge( $this->dependencies, is_array( $args[0] ) ? $args[0] : $args );
	}

	public function getName(): string {
		return $this->name;
	}

	/**
	 * Returns a message key for a message describing the parameter type.
	 */
	public function getTypeMessage(): string {
		$message = 'validator-type-' . $this->getType();

		if ( $this->isList() ) {
			$message .= '-list';
		}

		return $message;
	}

	/**
	 * @deprecated since 1.7
	 * Returns a list of dependencies the parameter has, in the form of
	 * other parameter names.
	 *
	 * @return string[]
	 */
	public function getDependencies(): array {
		return $this->dependencies;
	}

	public function isRequired(): bool {
		return is_null( $this->default );
	}

	public function isList(): bool {
		return $this->isList;
	}

	/**
	 * Returns the delimiter to use to split the raw value in case the
	 * parameter is a list.
	 */
	public function getDelimiter(): string {
		return $this->delimiter;
	}

	/**
	 * Sets the delimiter to use to split the raw value in case the
	 * parameter is a list.
	 */
	public function setDelimiter( string $delimiter ) {
		$this->delimiter = $delimiter;
	}

	/**
	 * Sets the parameter definition values contained in the provided array.
	 *
	 * @deprecated since 1.7
	 * TODO: provide alternative in ParamDefinitionFactory
	 */
	public function setArrayValues( array $param ) {
		if ( array_key_exists( 'aliases', $param ) ) {
			$this->addAliases( $param['aliases'] );
		}

		if ( array_key_exists( 'dependencies', $param ) ) {
			$this->addDependencies( $param['dependencies'] );
		}

		if ( array_key_exists( 'trim', $param ) ) {
			$this->trimValue = $param['trim'];
		}

		if ( array_key_exists( 'delimiter', $param ) ) {
			$this->delimiter = $param['delimiter'];
		}

		if ( array_key_exists( 'manipulatedefault', $param ) ) {
			$this->setDoManipulationOfDefault( $param['manipulatedefault'] );
		}

		$this->options = $param;
	}

	/**
	 * @see IParamDefinition::format
	 *
	 * @deprecated
	 *
	 * @param IParam $param
	 * @param IParamDefinition[] $definitions
	 * @param IParam[] $params
	 */
	public function format( IParam $param, array &$definitions, array $params ) {
		/**
		 * @var Param $param
		 */

		if ( $this->isList() && is_array( $param->getValue() ) ) {
			// TODO: if isList returns true, the value should be an array.
			// The second check here is to avoid a mysterious error.
			// Should have logging that writes down the value whenever this occurs.

			$values = $param->getValue();

			foreach ( $values as &$value ) {
				$value = $this->formatValue( $value, $param, $definitions, $params );
			}

			$param->setValue( $values );
			$this->formatList( $param, $definitions, $params );
		}
		else {
			$param->setValue( $this->formatValue( $param->getValue(), $param, $definitions, $params ) );
		}

		// deprecated, deriving classes should not add array-definitions to the list
		$definitions = self::getCleanDefinitions( $definitions );

		if ( array_key_exists( 'post-format', $this->options ) ) {
			$param->setValue( call_user_func( $this->options['post-format'], $param->getValue() ) );
		}
	}

	/**
	 * Formats the parameters values to their final result.
	 *
	 * @since 1.0
	 * @deprecated
	 *
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 */
	protected function formatList( IParam $param, array &$definitions, array $params ) {
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 1.0
	 * @deprecated
	 *
	 * @param mixed $value
	 * @param IParam $param
	 * @param IParamDefinition[] $definitions
	 * @param IParam[] $params
	 *
	 * @return mixed
	 */
	protected function formatValue( $value, IParam $param, array &$definitions, array $params ) {
		return $value;
	}

	/**
	 * Returns a cleaned version of the list of parameter definitions.
	 * This includes having converted all supported definition types to
	 * ParamDefinition classes and having all keys set to the names of the
	 * corresponding parameters.
	 *
	 * @deprecated since 1.7 - use ParamDefinitionFactory
	 *
	 * @param ParamDefinition[] $definitions
	 *
	 * @return ParamDefinition[]
	 * @throws Exception
	 */
	public static function getCleanDefinitions( array $definitions ): array {
		return ParamDefinitionFactory::singleton()->newDefinitionsFromArrays( $definitions );
	}

	/**
	 * Returns an identifier for the type of the parameter.
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Returns a ValueParser object to parse the parameters value.
	 */
	public function getValueParser(): ValueParser {
		if ( $this->parser === null ) {
			$this->parser = new NullParser();
		}

		return $this->parser;
	}

	/**
	 * Returns a ValueValidator that can be used to validate the parameters value.
	 */
	public function getValueValidator(): ValueValidator {
		if ( $this->validator === null ) {
			$this->validator = new NullValidator();
		}

		return $this->validator;
	}

	public function setValueParser( ValueParser $parser ) {
		$this->parser = $parser;
	}

	public function setValueValidator( ValueValidator $validator ) {
		$this->validator = $validator;
	}

	/**
	 * Sets a validation function that will be run before the ValueValidator.
	 *
	 * This can be used instead of a ValueValidator where validation is very
	 * trivial, ie checking if something is a boolean can be done with is_bool.
	 */
	public function setValidationCallback( ?callable $validationFunction ) {
		$this->validationFunction = $validationFunction;
	}

	/**
	 * @see setValidationCallback
	 */
	public function getValidationCallback(): ?callable {
		return $this->validationFunction;
	}

	public function getOptions(): array {
		return $this->options;
	}

}
