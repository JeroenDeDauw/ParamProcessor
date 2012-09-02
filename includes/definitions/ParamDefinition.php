<?php

/**
 * Parameter definition.
 * Specifies what kind of values are accepted, how they should be validated,
 * how they should be formatted, what their dependencies are and how they should be described.
 *
 * @since 1.0
 *
 * @file ParamDefinition.php
 * @ingroup Validator
 * @ingroup ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ParamDefinition implements IParamDefinition {

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
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * The default value for the parameter, or null when the parameter is required.
	 *
	 * @since 1.0
	 *
	 * @var mixed
	 */
	protected $default;

	/**
	 * The main name of the parameter.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @since 1.0
	 * @var boolean
	 */
	protected $isList;

	/**
	 * @since 1.0
	 * @var string
	 */
	protected $delimiter = ',';

	/**
	 * List of aliases for the parameter name.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $aliases = array();

	/**
	 * List of criteria the parameter value needs to hold against.
	 *
	 * @since 1.0
	 *
	 * @var array of ParameterCriterion
	 */
	protected $criteria = array();

	/**
	 * List of manipulations the parameter value needs to undergo.
	 *
	 * @since 1.0
	 *
	 * @var array of ParameterManipulation
	 */
	protected $manipulations = array();

	/**
	 * A message that acts as description for the parameter or false when there is none.
	 * Can be obtained via getMessage and set via setMessage.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	protected $message = 'validator-message-nodesc';

	/**
	 * Original array definition of the parameter
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param string $name
	 * @param mixed $default Use null for no default (which makes the parameter required)
	 * @param string $message
	 * @param boolean $isList
	 */
	public function __construct( $name, $default = null, $message = null, $isList = false ) {
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
	 * @since boolean|null
	 */
	public function trimDuringClean() {
		return $this->trimValue;
	}

	/**
	 * Returns the criteria that apply to the list as a whole.
	 *
	 * @deprecated since 1.0, removal in 1.1
	 *
	 * @return array
	 */
	public function getListCriteria() {
		return array(); // TODO
	}

	/**
	 * @see IParamDefinition::getAliases
	 *
	 * @since 1.0
	 *
	 * @return array
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
	 * Returns the list of allowed values, or false if there is no such restriction.
	 *
	 * @since 1.0
	 *
	 * @return array|false
	 */
	public function getAllowedValues() {
		return $this->allowedValues;
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
	 * Returns a description message for the parameter, or false when there is none.
	 * Override in deriving classes to add a message.
	 *
	 * @since 1.0
	 * @deprecated since 1.0, removal in 1.1
	 *
	 * @return mixed string or false
	 */
	public function getDescription() {
		return wfMsg( $this->message );
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
	 * @param mixed $aliases string or array of string
	 */
	public function addAliases( $aliases ) {
		$args = func_get_args();
		$this->aliases = array_merge( $this->aliases, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * @see IParamDefinition::addCriteria
	 *
	 * @deprecated since 1.0, removal in 1.1
	 * @since 1.0
	 *
	 * @param mixed $criteria ParameterCriterion or array of ParameterCriterion
	 */
	public function addCriteria( $criteria ) {
		$args = func_get_args();
		$this->criteria = array_merge( $this->criteria, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * @see IParamDefinition::addDependencies
	 *
	 * @since 1.0
	 *
	 * @param mixed $dependencies string or array of string
	 */
	public function addDependencies( $dependencies ) {
		$args = func_get_args();
		$this->dependencies = array_merge( $this->dependencies, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * @see IParamDefinition::addManipulations
	 *
	 * @deprecated since 1.0, removal in 1.1
	 * @since 1.0
	 *
	 * @param mixed $manipulations ParameterManipulation or array of ParameterManipulation
	 */
	public function addManipulations( $manipulations ) {
		$args = func_get_args();
		$this->manipulations = array_merge( $this->manipulations, is_array( $args[0] ) ? $args[0] : $args );
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
	 * @return array
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
	 * @see IParamDefinition::getCriteria
	 *
	 * @deprecated since 1.0, removal in 1.1
	 * @since 1.0
	 *
	 * @return array of ParameterCriterion
	 */
	public function getCriteria() {
		return $this->criteria;
	}

	/**
	 * @see IParamDefinition::getManipulations
	 *
	 * @deprecated since 1.0, removal in 1.1
	 * @since 1.0
	 *
	 * @return array of ParameterManipulation
	 */
	public function getManipulations() {
		return $this->manipulations;
	}

	/**
	 * @see IParamDefinition::getDelimiter
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getDelimiter() {
		return $this->delimiter;
	}

	/**
	 * @see IParamDefinition::setDelimiter
	 *
	 * @since 1.0
	 *
	 * @param $delimiter string
	 */
	public function setDelimiter( $delimiter ) {
		$this->delimiter = $delimiter;
	}

	/**
	 * Gets the criteria for the type of the parameter.
	 *
	 * @deprecated since 1.0, removal in 1.1
	 * @since 1.0
	 *
	 * @return array
	 */
	protected function getCriteriaForType() {
		$criteria = array();

		switch( $this->type ) {
			case self::TYPE_INTEGER:
				$criteria[] = new CriterionIsInteger();
				break;
			case self::TYPE_FLOAT:
				$criteria[] = new CriterionIsFloat();
				break;
			case self::TYPE_NUMBER: // Note: This accepts non-decimal notations!
				$criteria[] = new CriterionIsNumeric();
				break;
			case self::TYPE_BOOLEAN:
				// TODO: work with list of true and false values and i18n.
				$criteria[] = new CriterionInArray( 'yes', 'no', 'on', 'off', '1', '0' );
				break;
			case self::TYPE_CHAR:
				$criteria[] = new CriterionHasLength( 1, 1 );
				break;
			case self::TYPE_TITLE:
				$criteria[] = new CriterionIsTitle();
				break;
			case self::TYPE_STRING: default:
			// No extra criteria for strings.
			break;
		}

		return $criteria;
	}

	/**
	 * @deprecated Compatibility helper, will be removed in 1.1.
	 * @since 1.0
	 *
	 * @param Parameter $parameter
	 *
	 * @return IParamDefinition
	 */
	public static function newFromParameter( Parameter $parameter ) {
		$def = self::newFromType(
			$parameter->getType(),
			$parameter->getName(),
			$parameter->getDefault(),
			$parameter->getMessage() === false ? 'validator-message-nodesc' : $parameter->getMessage(),
			$parameter->isList()
		);

		$def->addAliases( $parameter->getAliases() );
		$def->addCriteria( $parameter->getCriteria() );
		$def->addManipulations( $parameter->getManipulations() );
		$def->addDependencies( $parameter->getDependencies() );
		$def->setDoManipulationOfDefault( $parameter->applyManipulationsToDefault );

		if ( $parameter->isList() ) {
			$def->setDelimiter( $parameter->getDelimiter() );
		}

		$def->trimValue = $parameter->trimValue;

		return $def;
	}

	/**
	 * Construct a new ParamDefinition from an array.
	 *
	 * @since 1.0
	 *
	 * @param array $param
	 * @param bool $getMad
	 *
	 * @return IParamDefinition|false
	 * @throws MWException
	 */
	public static function newFromArray( array $param, $getMad = true ) {
		foreach ( array( 'name', 'message' ) as $requiredElement ) {
			if ( !array_key_exists( $requiredElement, $param ) ) {
				if ( $getMad ) {
					throw new MWException( 'Could not construct a ParamDefinition from an array without ' . $requiredElement . ' element' );
				}

				return false;
			}
		}

		$parameter = self::newFromType(
			array_key_exists( 'type', $param ) ? $param['type'] : 'string',
			$param['name'],
			array_key_exists( 'default', $param ) ? $param['default'] : null,
			$param['message'],
			array_key_exists( 'islist', $param ) ? $param['islist'] : false
		);

		$parameter->setArrayValues( $param );

		return $parameter;
	}

	/**
	 * Sets the parameter definition values contained in the provided array.
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

		// Backward compatibility code, will be removed in 1.1.
		if ( array_key_exists( 'manipulations', $param ) ) {
			$this->addManipulations( $param['manipulations'] );
		}

		// Backward compatibility code, will be removed in 1.1.
		if ( array_key_exists( 'criteria', $param ) ) {
			$this->addCriteria( $param['criteria'] );
		}

		// TODO: public method + keep lists in sync
		$this->options = $param;
	}

	/**
	 * @see IParamDefinition::validate
	 *
	 * @since 1.0
	 *
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 * @param ValidatorOptions $options
	 *
	 * @return array|true
	 *
	 * TODO: return error list (ie Status object)
	 */
	public function validate( IParam $param, array $definitions, array $params, ValidatorOptions $options ) {
		if ( $this->isList() ) {
			$valid = true;
			$values = $param->getValue();

			foreach ( $values as $value ) {
				// TODO: restore not bailing out at one error in list but filtering on valid
				$valid = $this->validateValue( $value, $param, $definitions, $params, $options );

				if ( !$valid ) {
					break;
				}
			}

			return $valid && $this->validateList( $param, $definitions, $params, $options );
		}
		else {
			$valid = $this->validateValue( $param->getValue(), $param, $definitions, $params, $options );

			return $valid ? true : array( new ValidationError( 'Error' ) ); // TODO
		}
	}

	/**
	 * @see IParamDefinition::format
	 *
	 * @since 1.0
	 *
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 */
	public function format( IParam $param, array &$definitions, array $params ) {
		if ( $this->isList() ) {
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
	}

	/**
	 * Formats the parameters values to their final result.
	 *
	 * @since 1.0
	 *
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 */
	protected function formatList( IParam $param, array &$definitions, array $params ) {
		// TODO
	}

	/**
	 * Validates the parameters value set.
	 *
	 * @since 1.0
	 *
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 * @param ValidatorOptions $options
	 *
	 * @return boolean
	 */
	protected function validateList( IParam $param, array $definitions, array $params, ValidatorOptions $options ) {
		// TODO
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 1.0
	 *
	 * @param $value mixed
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 *
	 * @return mixed
	 */
	protected function formatValue( $value, IParam $param, array &$definitions, array $params ) {
		// No-op
	}

	/**
	 * Validates the parameters value and returns the result.
	 * @see ParamDefinition::validateValue
	 *
	 * @since 1.0
	 *
	 * @param $value mixed
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 * @param ValidatorOptions $options
	 *
	 * @return boolean
	 */
	protected function validateValue( $value, IParam $param, array $definitions, array $params, ValidatorOptions $options ) {
		return true; // FIXME
	}

	/**
	 * Compatibility helper method, will be removed in 1.1.
	 *
	 * @deprecated
	 * @since 1.0
	 *
	 * @return Parameter
	 */
	public function toParameter() {
		if ( $this->isList() ) {
			$parameter = new ListParameter(
				$this->getName(),
				$this->getDelimiter(),
				$this->getType(),
				$this->getDefault(),
				$this->getAliases(),
				$this->getCriteria()
			);
		}
		else {
			$parameter = new Parameter(
				$this->getName(),
				$this->getType(),
				$this->getDefault(),
				$this->getAliases(),
				$this->getCriteria(),
				$this->getDependencies()
			);
		}

		$parameter->addManipulations( $this->getManipulations() );
		$parameter->setDoManipulationOfDefault( $this->applyManipulationsToDefault );

		return $parameter;
	}

	/**
	 * Returns a cleaned version of the list of parameter definitions.
	 * This includes having converted all supported definition types to
	 * ParamDefinition classes and having all keys set to the names of the
	 * corresponding parameters.
	 *
	 *
	 * @since 1.0
	 *
	 * @param $definitions array of IParamDefinition
	 *
	 * @return array
	 * @throws MWException
	 */
	public static function getCleanDefinitions( array $definitions ) {
		$cleanList = array();

		foreach ( $definitions as $key => $definition ) {
			if ( is_array( $definition ) ) {
				if ( !array_key_exists( 'name', $definition ) && is_string( $key ) ) {
					$definition['name'] = $key;
				}

				$definition = ParamDefinition::newFromArray( $definition );
			}
			elseif ( $definition instanceof Parameter ) {
				// This if for backwards compat, will be removed in 1.1.
				$definition = ParamDefinition::newFromParameter( $definition );
			}

			if ( !( $definition instanceof ParamDefinition ) ) {
				throw new MWException( '$definition not an instance of ParamDefinition' );
			}

			$cleanList[$definition->getName()] = $definition;
		}

		return $cleanList;
	}

	/**
	 * @see IParamDefinition::getType()
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getType() {
		global $egParamDefinitions;

		static $classToType = false;

		if ( $classToType === false ) {
			$classToType = array_flip( $egParamDefinitions );
		}

		$class = $class = function_exists( 'get_called_class' ) ? get_called_class() : self::get_called_class();

		if ( !array_key_exists( $class, $classToType ) ) {
			$egParamDefinitions['class-' . $class] = $class;
			$classToType = false;
			return $this->getType();
		}

		return $classToType[$class];
	}

	/**
	 * Creates a new instance of a ParamDefinition based on the provided type.
	 *
	 * @since 1.0
	 *
	 * @param string $type
	 * @param string $name
	 * @param mixed $default
	 * @param string $message
	 * @param boolean $isList
	 *
	 * @return IParamDefinition
	 */
	public static function newFromType( $type, $name, $default, $message, $isList = false ) {
		global $egParamDefinitions;

		if ( !array_key_exists( $type, $egParamDefinitions ) ) {
			throw new MWException( 'Unknown parameter type "' . $type . '".' );
		}

		$class = $egParamDefinitions[$type];

		return new $class(
			$name,
			$default,
			$message,
			$isList
		);
	}

	/**
	 * Compatibility fallback function so the singleton method works on PHP < 5.3.
	 * Code borrowed from http://www.php.net/manual/en/function.get-called-class.php#107445
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	private static function get_called_class() {
		$bt = debug_backtrace();
		$l = count($bt) - 1;
		$matches = array();
		while(empty($matches) && $l > -1){
			$lines = file($bt[$l]['file']);
			$callerLine = $lines[$bt[$l]['line']-1];
			preg_match('/([a-zA-Z0-9\_]+)::'.$bt[$l--]['function'].'/',
				$callerLine,
				$matches);
		}
		if (!isset($matches[1])) $matches[1]=NULL; //for notices
		if ($matches[1] == 'self') {
			$line = $bt[$l]['line']-1;
			while ($line > 0 && strpos($lines[$line], 'class') === false) {
				$line--;
			}
			preg_match('/class[\s]+(.+?)[\s]+/si', $lines[$line], $matches);
		}
		return $matches[1];
	}

	/**
	 * @see IParamDefinition::getValueParser
	 *
	 * @since 1.0
	 *
	 * @param ValidatorOptions $options
	 *
	 * @return ValueParser
	 */
	public function getValueParser( ValidatorOptions $options ) {
		return $options->isStringlyTyped() ? new BoolParser() : new NullParser();
	}

	/**
	 * Returns a StringValueParser that can parse string representations of this parameter type.
	 *
	 * @since 1.0
	 *
	 * @return StringValueParser
	 */
	protected abstract function getStringValueParser();

	/**
	 * @since 1.0
	 *
	 * @return ValueValidator
	 */
	protected function getValidator() {
		return new ValueValidatorObject();
	}

	/**
	 * Returns a ValueValidator that can be used to validate the parameters value.
	 *
	 * @since 1.0
	 *
	 * @return ValueValidator
	 */
	public function getValueValidator() {
		$valueValidator = $this->getValidator();
		$valueValidator->setOptions( $this->options );
		return $valueValidator;
	}

}
