<?php

/**
 * Parameter definition.
 * Specifies what kind of values are accepted, how they should be validated,
 * how they should be formatted, what their dependencies are and how they should be described.
 *
 * @since 0.5
 *
 * @file ParamDefinition.php
 * @ingroup Validator
 * @ingroup ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ParamDefinition implements iParamDefinition {

	/**
	 * Maps the type identifiers to their corresponding classes.
	 * TODO: have registration system
	 *
	 * @since 0.5
	 *
	 * @var array
	 */
	protected static $typeMap = array(
		'boolean' => 'BoolParam', // Parameter::TYPE_BOOLEAN
		'char' => 'CharParam', // Parameter::TYPE_CHAR
		'float' => 'FloatParam', // Parameter::TYPE_FLOAT
		'integer' => 'IntParam', // Parameter::TYPE_INTEGER
		'string' => 'StringParam', // Parameter::TYPE_STRING
		'title' => 'TitleParam', // Parameter::TYPE_TITLE
	);

	/**
	 * Indicates whether parameters that are provided more then once  should be accepted,
	 * and use the first provided value, or not, and generate an error.
	 *
	 * @since 0.5
	 *
	 * @var boolean
	 */
	public static $acceptOverriding = false;

	/**
	 * Indicates whether parameters not found in the criteria list
	 * should be stored in case they are not accepted. The default is false.
	 *
	 * @since 0.5
	 *
	 * @var boolean
	 */
	public static $accumulateParameterErrors = false;

	/**
	 * Indicates if the parameter value should trimmed.
	 * This is done BEFORE the validation process.
	 *
	 * @since 0.5
	 *
	 * @var boolean
	 */
	protected $trimValue = true;

	/**
	 * Indicates if the parameter manipulations should be applied to the default value.
	 *
	 * @since 0.5
	 *
	 * @var boolean
	 */
	protected $applyManipulationsToDefault = true;

	/**
	 * Dependency list containing parameters that need to be handled before this one.
	 *
	 * @since 0.5
	 *
	 * @var array
	 */
	protected $dependencies = array();

	/**
	 * The default value for the parameter, or null when the parameter is required.
	 *
	 * @since 0.5
	 *
	 * @var mixed
	 */
	protected $default;

	/**
	 * The main name of the parameter.
	 *
	 * @since 0.5
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @since 0.5
	 * @var boolean
	 */
	protected $isList;

	/**
	 * @since 0.5
	 * @var string
	 */
	protected $delimiter = ',';

	/**
	 * List of aliases for the parameter name.
	 *
	 * @since 0.5
	 *
	 * @var array
	 */
	protected $aliases = array();

	/**
	 * List of criteria the parameter value needs to hold against.
	 *
	 * @since 0.5
	 *
	 * @var array of ParameterCriterion
	 */
	protected $criteria = array();

	/**
	 * List of manipulations the parameter value needs to undergo.
	 *
	 * @since 0.5
	 *
	 * @var array of ParameterManipulation
	 */
	protected $manipulations = array();

	/**
	 * A message that acts as description for the parameter or false when there is none.
	 * Can be obtained via getMessage and set via setMessage.
	 *
	 * @since 0.5
	 *
	 * @var mixed string or false
	 */
	protected $message = false;

	/**
	 * A list of allowed values. This means the parameters value(s) must be in the list
	 * during validation. False for no restriction.
	 *
	 * @since 0.5
	 *
	 * @var array|false
	 */
	protected $allowedValues = false;

	/**
	 * A list of prohibited values. This means the parameters value(s) must
	 * not be in the list during validation. False for no restriction.
	 *
	 * @since 0.5
	 *
	 * @var array|false
	 */
	protected $prohibitedValues = false;

	/**
	 * Constructor.
	 *
	 * @since 0.5
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
	}

	/**
	 * Returns if the value should be trimmed before validation and any further processing.
	 *
	 * @since 0.5
	 *
	 * @since boolean
	 */
	public function trimBeforeValidate() {
		$this->trimValue;
	}

	/**
	 * Returns the criteria that apply to the list as a whole.
	 *
	 * @deprecated since 0.5, removal in 0.7
	 *
	 * @return array
	 */
	public function getListCriteria() {
		return array(); // TODO
	}

	/**
	 * Returns the parameter name aliases.
	 *
	 * @since 0.5
	 *
	 * @return array
	 */
	public function getAliases() {
		return $this->aliases;
	}

	/**
	 * Returns if the parameter has a certain alias.
	 *
	 * @since 0.5
	 *
	 * @param string $alias
	 *
	 * @return boolean
	 */
	public function hasAlias( $alias ) {
		return in_array( $alias, $this->getAliases() );
	}

	/**
	 * Returns if the parameter has a certain dependency.
	 *
	 * @since 0.5
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
	 * @since 0.5
	 *
	 * @return array|false
	 */
	public function getAllowedValues() {
		return $this->allowedValues;
	}

	/**
	 * Sets the default parameter value. Null indicates no default,
	 * and therefore makes the parameter required.
	 *
	 * @since 0.5
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
	 *
	 * @since 0.5
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
	 * @since 0.5
	 * @deprecated since 0.5, removal in 0.7
	 *
	 * @return mixed string or false
	 */
	public function getDescription() {
		return wfMsg( $this->message );
	}

	/**
	 * Returns a message that will act as a description message for the parameter, or false when there is none.
	 * Override in deriving classes to add a message.
	 *
	 * @since 0.5
	 *
	 * @return mixed string or false
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Sets a message for the parameter that will act as description.
	 * This should be a message key, ie something that can be passed
	 * to wfMsg. Not an actual text. If you do not have a message key,
	 * but only a text, use setDescription instead.
	 *
	 * @since 0.5
	 *
	 * @param string $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	/**
	 * Set if the parameter manipulations should be applied to the default value.
	 *
	 * @since 0.5
	 *
	 * @param boolean $doOrDoNotThereIsNoTry
	 */
	public function setDoManipulationOfDefault( $doOrDoNotThereIsNoTry ) {
		$this->applyManipulationsToDefault = $doOrDoNotThereIsNoTry;
	}

	/**
	 * Returns if the parameter manipulations should be applied to the default value.
	 * TODO: have fromArray support.
	 *
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function shouldManipulateDefault() {
		return $this->applyManipulationsToDefault;
	}

	/**
	 * Adds one or more aliases for the parameter name.
	 *
	 * @since 0.5
	 *
	 * @param mixed $aliases string or array of string
	 */
	public function addAliases( $aliases ) {
		$args = func_get_args();
		$this->aliases = array_merge( $this->aliases, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * Adds one or more ParameterCriterion.
	 *
	 * @since 0.5
	 *
	 * @param mixed $criteria ParameterCriterion or array of ParameterCriterion
	 */
	public function addCriteria( $criteria ) {
		$args = func_get_args();
		$this->criteria = array_merge( $this->criteria, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * Adds one or more dependencies. There are the names of parameters
	 * that need to be validated and formatted before this one.
	 *
	 * @since 0.5
	 *
	 * @param mixed $dependencies string or array of string
	 */
	public function addDependencies( $dependencies ) {
		$args = func_get_args();
		$this->dependencies = array_merge( $this->dependencies, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * Adds one or more ParameterManipulation.
	 *
	 * @since 0.5
	 *
	 * @param mixed $manipulations ParameterManipulation or array of ParameterManipulation
	 */
	public function addManipulations( $manipulations ) {
		$args = func_get_args();
		$this->manipulations = array_merge( $this->manipulations, is_array( $args[0] ) ? $args[0] : $args );
	}

	/**
	 * Returns the parameters main name.
	 *
	 * @since 0.5
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Returns an internationalized message indicating the parameter type suited for display to users.
	 *
	 * @since 0.5
	 *
	 * @return string
	 */
	public function getTypeMessage() {
		global $wgLang;

		$message = wfMsg( 'validator-type-' . $this->getType() );

		return $this->isList() ?
			wfMsgExt( 'validator-describe-listtype', 'parsemag', $message )
			: $wgLang->ucfirst( $message );
	}

	/**
	 * Returns a list of dependencies the parameter has, in the form of
	 * other parameter names.
	 *
	 * @since 0.5
	 *
	 * @return array
	 */
	public function getDependencies() {
		return $this->dependencies;
	}

	/**
	 * Returns if the parameter is a required one or not.
	 *
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function isRequired() {
		return is_null( $this->default );
	}

	/**
	 * Returns if the parameter is a list or not.
	 *
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function isList() {
		return $this->isList;
	}

	/**
	 * Returns the parameter criteria.
	 *
	 * @deprecated since 0.5, removal in 0.7
	 * @since 0.5
	 *
	 * @return array of ParameterCriterion
	 */
	public function getCriteria() {
		return $this->criteria;
	}

	/**
	 * Returns the parameter manipulations.
	 *
	 * @deprecated since 0.5, removal in 0.7
	 * @since 0.5
	 *
	 * @return array of ParameterManipulation
	 */
	public function getManipulations() {
		return $this->manipulations;
	}

	/**
	 * Returns the delimiter to use to split the raw value in case the
	 * parameter is a list.
	 *
	 * @since 0.5
	 *
	 * @return string
	 */
	public function getDelimiter() {
		return $this->delimiter;
	}

	/**
	 * Sets the delimiter to use to split the raw value in case the
	 * parameter is a list.
	 *
	 * @since 0.5
	 *
	 * @param $delimiter string
	 */
	public function setDelimiter( $delimiter ) {
		$this->delimiter = $delimiter;
	}

	/**
	 * Gets the criteria for the type of the parameter.
	 *
	 * @deprecated since 0.5, removal in 0.7
	 * @since 0.5
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
	 * Creates a new instance of a ParamDefinition based on the provided type.
	 *
	 * @since 0.5
	 *
	 * @param string $type
	 * @param string $name
	 * @param mixed $default
	 * @param string $message
	 * @param boolean $isList
	 *
	 * @return ParamDefinition
	 */
	public static function newFromType( $type, $name, $default, $message, $isList = false ) {
		$class = self::$typeMap[$type];

		return new $class(
			$name,
			$default,
			$message,
			$isList
		);
	}

	/**
	 * @deprecated Compatibility helper, will be removed in 0.7.
	 * @since 0.5
	 *
	 * @param Parameter $parameter
	 *
	 * @return ParamDefinition
	 */
	public static function newFromParameter( Parameter $parameter ) {
		$def = self::newFromType(
			$parameter->getType(),
			$parameter->getName(),
			$parameter->getDefault(),
			$parameter->getMessage(),
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
	 * @since 0.5
	 *
	 * @param array $param
	 * @param bool $getMad
	 *
	 * @return ParamDefinition|false
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
	 * @since 0.5
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

		if ( array_key_exists( 'values', $param ) ) {
			$this->allowedValues = $param['values'];
		}

		if ( array_key_exists( 'excluding', $param ) ) {
			$this->prohibitedValues = $param['excluding'];
		}

		if ( array_key_exists( 'delimiter', $param ) ) {
			$this->delimiter = $param['delimiter'];
		}
	}

	/**
	 * Validates the parameters value.
	 *
	 * @since 0.5
	 *
	 * @param $param iParam
	 * @param $definitions array of iParamDefinition
	 * @param $params array of iParam
	 *
	 * @return array|true
	 *
	 * TODO: return error list (ie Status object)
	 */
	public function validate( iParam $param, array $definitions, array $params ) {
		if ( $this->isList() ) {
			$valid = true;
			$values = $param->getValue();

			foreach ( $values as $value ) {
				// TODO: restore not bailing out at one error in list but filtering on valid
				$valid = $this->validateValue( $value, $param, $definitions, $params );

				if ( !$valid ) {
					break;
				}
			}

			return $valid && $this->validateList( $param, $definitions, $params );
		}
		else {
			return $this->validateValue( $param->getValue(), $param, $definitions, $params );
		}
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param $param iParam
	 * @param $definitions array of iParamDefinition
	 * @param $params array of iParam
	 */
	public function format( iParam $param, array &$definitions, array $params ) {
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
	 * @since 0.5
	 *
	 * @param $param iParam
	 * @param $definitions array of iParamDefinition
	 * @param $params array of iParam
	 */
	protected function formatList( iParam $param, array &$definitions, array $params ) {
		// TODO
	}

	/**
	 * Validates the parameters value set.
	 *
	 * @since 0.5
	 *
	 * @param $param iParam
	 * @param $definitions array of iParamDefinition
	 * @param $params array of iParam
	 *
	 * @return boolean
	 */
	protected function validateList( iParam $param, array $definitions, array $params ) {
		// TODO
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param $value mixed
	 * @param $param iParam
	 * @param $definitions array of iParamDefinition
	 * @param $params array of iParam
	 *
	 * @return mixed
	 */
	protected function formatValue( $value, iParam $param, array &$definitions, array $params ) {
		// No-op
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param $value mixed
	 * @param $param iParam
	 * @param $definitions array of iParamDefinition
	 * @param $params array of iParam
	 *
	 * @return boolean
	 */
	protected function validateValue( $value, iParam $param, array $definitions, array $params ) {
		if ( $this->allowedValues !== false && !in_array( $value, $this->allowedValues ) ) {
			return false;
		}

		if ( $this->prohibitedValues !== false && in_array( $value, $this->prohibitedValues ) ) {
			return false;
		}
	}

	/**
	 * Compatibility helper method, will be removed in 0.7.
	 *
	 * @deprecated
	 * @since 0.5
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
	 * @since 0.5
	 *
	 * @param $definitions array of iParamDefinition
	 *
	 * @return array
	 * @throws MWException
	 */
	public static function getCleanDefinitions( array $definitions ) {
		$cleanList = array();

		foreach ( $definitions as $definition ) {
			if ( is_array( $definition ) ) {
				$definition = ParamDefinition::newFromArray( $definition );
			}
			elseif ( $definition instanceof Parameter ) {
				// This if for backwards compat, will be removed in 0.7.
				$definition = ParamDefinition::newFromParameter( $definition );
			}

			if ( !( $definition instanceof ParamDefinition ) ) {
				throw new MWException( '$definition not an instance of ParamDefinition' );
			}

			$cleanList[$definition->getName()] = $definition;
		}

		return $cleanList;
	}

}