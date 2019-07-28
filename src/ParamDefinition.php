<?php

namespace ParamProcessor;

use Exception;

use ValueParsers\ValueParser;
use ValueParsers\NullParser;

use ValueValidators\ValueValidator;
use ValueValidators\NullValidator;

/**
 * Parameter definition.
 * Specifies what kind of values are accepted, how they should be validated,
 * how they should be formatted, what their dependencies are and how they should be described.
 *
 * Try to avoid using this interface outside of ParamProcessor for anything else then defining parameters.
 * In particular, do not derive from this class to implement methods such as formatValue.
 *
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinition implements IParamDefinition {

	/**
	 * Indicates whether parameters that are provided more then once  should be accepted,
	 * and use the first provided value, or not, and generate an error.
	 *
	 * @since 1.0
	 *
	 * @var boolean
	 */
	public static $acceptOverriding = false;

	/**
	 * Indicates whether parameters not found in the criteria list
	 * should be stored in case they are not accepted. The default is false.
	 *
	 * @since 1.0
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
	protected $message = 'validator-message-nodesc';

	/**
	 * Indicates if the parameter value should trimmed during the clean process.
	 *
	 * @since 1.0
	 *
	 * @var boolean|null
	 */
	protected $trimValue = null;

	/**
	 * Indicates if the parameter manipulations should be applied to the default value.
	 *
	 * @since 1.0
	 *
	 * @var boolean
	 */
	protected $applyManipulationsToDefault = true;

	/**
	 * Dependency list containing parameters that need to be handled before this one.
	 *
	 * @since 1.0
	 *
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
		$this->message = $message;
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
	 * @see IParamDefinition::trimDuringClean
	 *
	 * @since 1.0
	 *
	 * @return boolean|null
	 */
	public function trimDuringClean() {
		return $this->trimValue;
	}

	/**
	 * @see IParamDefinition::getAliases
	 *
	 * @since 1.0
	 *
	 * @return string[]
	 */
	public function getAliases() {
		return $this->aliases;
	}

	/**
	 * @see IParamDefinition::hasAlias
	 *
	 * @since 1.0
	 *
	 * @param string $alias
	 *
	 * @return boolean
	 */
	public function hasAlias( $alias ) {
		return in_array( $alias, $this->getAliases() );
	}

	/**
	 * @see IParamDefinition::hasDependency
	 *
	 * @since 1.0
	 *
	 * @param string $dependency
	 *
	 * @return boolean
	 */
	public function hasDependency( $dependency ) {
		return in_array( $dependency, $this->getDependencies() );
	}

	/**
	 * Returns the list of allowed values, or an empty array if there is no such restriction.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function getAllowedValues() {
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
	 * @see IParamDefinition::setDefault
	 *
	 * @since 1.0
	 *
	 * @param mixed $default
	 * @param boolean $manipulate Should the default be manipulated or not? Since 0.4.6.
	 */
	public function setDefault( $default, $manipulate = true ) {
		$this->default = $default;
		$this->setDoManipulationOfDefault( $manipulate );
	}

	/**
	 * @see IParamDefinition::getDefault
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * @see IParamDefinition::getMessage
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * @see IParamDefinition::setMessage
	 *
	 * @since 1.0
	 *
	 * @param string $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	/**
	 * @see IParamDefinition::setDoManipulationOfDefault
	 *
	 * @since 1.0
	 *
	 * @param boolean $doOrDoNotThereIsNoTry
	 */
	public function setDoManipulationOfDefault( $doOrDoNotThereIsNoTry ) {
		$this->applyManipulationsToDefault = $doOrDoNotThereIsNoTry;
	}

	/**
	 * @see IParamDefinition::shouldManipulateDefault
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function shouldManipulateDefault() {
		return $this->applyManipulationsToDefault;
	}

	/**
	 * @see IParamDefinition::addAliases
	 *
	 * @since 1.0
	 *
	 * @param string|string[] $aliases
	 */
	public function addAliases( $aliases ) {
		$args = func_get_args();
		$this->aliases = array_merge( $this->aliases, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * @see IParamDefinition::addDependencies
	 *
	 * @since 1.0
	 *
	 * @param string|string[] $dependencies
	 */
	public function addDependencies( $dependencies ) {
		$args = func_get_args();
		$this->dependencies = array_merge( $this->dependencies, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * @see IParamDefinition::getName
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns a message key for a message describing the parameter type.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getTypeMessage() {
		$message = 'validator-type-' . $this->getType();

		if ( $this->isList() ) {
			$message .= '-list';
		}

		return $message;
	}

	/**
	 * @see IParamDefinition::getDependencies
	 *
	 * @since 1.0
	 *
	 * @return string[]
	 */
	public function getDependencies() {
		return $this->dependencies;
	}

	/**
	 * @see IParamDefinition::isRequired
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function isRequired() {
		return is_null( $this->default );
	}

	/**
	 * @see IParamDefinition::isList
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function isList() {
		return $this->isList;
	}

	/**
	 * @see IParamDefinition::getDelimiter
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getDelimiter(): string {
		return $this->delimiter;
	}

	/**
	 * Sets the delimiter to use to split the raw value in case the
	 * parameter is a list.
	 *
	 * @param $delimiter string
	 */
	public function setDelimiter( $delimiter ) {
		$this->delimiter = $delimiter;
	}

	/**
	 * @see IParamDefinition::setArrayValues
	 *
	 * @since 1.0
	 *
	 * @param array $param
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
	 * @since 1.0
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
	 * @since 1.0
	 *
	 * @param ParamDefinition[] $definitions
	 *
	 * @return ParamDefinition[]
	 * @throws Exception
	 */
	public static function getCleanDefinitions( array $definitions ): array {
		$cleanList = [];

		foreach ( $definitions as $key => $definition ) {
			if ( is_array( $definition ) ) {
				if ( !array_key_exists( 'name', $definition ) && is_string( $key ) ) {
					$definition['name'] = $key;
				}

				$definition = ParamDefinitionFactory::singleton()->newDefinitionFromArray( $definition );
			}

			if ( !( $definition instanceof IParamDefinition ) ) {
				throw new Exception( '$definition not an instance of IParamDefinition' );
			}

			$cleanList[$definition->getName()] = $definition;
		}

		return $cleanList;
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
