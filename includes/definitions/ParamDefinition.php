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
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinition {

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
	 *
	 * @since 0.5
	 *
	 * @var boolean
	 */
	public $trimValue = true;

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
	 * @since 0.5.9
	 *
	 * @var mixed string or false
	 */
	protected $message = false;


	/**
	 * Returns the criteria that apply to the list as a whole.
	 *
	 * @since 0.5
	 *
	 * @return array
	 */
	public function getListCriteria() {
		return array();
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
	 * @deprecated
	 *
	 * @return mixed string or false
	 */
	public function getDescription() {
		return wfMsg( $this->message );
	}

	/**
	 * Sets a description for the parameter.
	 * This is a string describing the parameter, if you have a message
	 * key, ie something that can be passed to wfMsg, then use the
	 * setMessage method instead.
	 *
	 * @since 0.5
	 *
	 * @param string $descriptionMessage
	 */
	public function setDescription( $descriptionMessage ) {
		$this->description = $descriptionMessage;
	}

	/**
	 * Returns a message that will act as a description message for the parameter, or false when there is none.
	 * Override in deriving classes to add a message.
	 *
	 * @since 0.5.9
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
	 * @since 0.5.9
	 *
	 * @param string $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
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
	 * Returns the type of the parameter.
	 *
	 * @since 0.5
	 *
	 * @return string element of the Parameter::TYPE_ enum
	 */
	public function getType() {
		return $this->type;
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

		$message = wfMsg( 'validator-type-' . $this->type );
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
		return array_merge( $this->getCriteriaForType(), $this->criteria );
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
		return array_merge( $this->getManipulationsForType(), $this->manipulations );
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

	public static function newFromParameter( Parameter $parameter ) {
		$class = self::$typeMap[$parameter->getType()];

		$def = new $class(
			$parameter->getName(),
			$parameter->getDefault(),
			$parameter->getMessage(),
			$parameter->isList()
		);

		$def->addAliases( $parameter->getAliases() );
		$def->addCriteria( $parameter->getCriteria() );
		$def->addManipulations( $parameter->getManipulations() );
		$def->addDependencies( $parameter->getDependencies() );

		return $def;
	}

	public static function newFromArray( array $param, $getMad = true ) {
		foreach ( array( 'type', 'name', 'message' ) as $requiredElement ) {
			if ( !array_key_exists( $requiredElement, $param ) ) {
				if ( $getMad ) {
					throw new MWException( 'Could not construct a ParamDefinition from an array without ' . $requiredElement . ' element' );
				}

				return false;
			}
		}

		$class = self::$typeMap[$param['type']];

		$parameter = new $class(
			$param['name'],
			array_key_exists( 'default', $param ) ? $param['default'] : null,
			$param['message'],
			array_key_exists( 'islist', $param ) ? $param['islist'] : false
		);

		if ( array_key_exists( 'aliases', $param ) ) {
			$parameter->addAliases( $param['aliases'] );
		}

		if ( array_key_exists( 'dependencies', $param ) ) {
			$parameter->addAliases( $param['dependencies'] );
		}

		return $parameter;
	}

	protected static $typeMap = array(
		Parameter::TYPE_BOOLEAN => 'BoolParam',
		Parameter::TYPE_CHAR => 'CharParam',
		Parameter::TYPE_FLOAT => 'FloatParam',
		Parameter::TYPE_INTEGER => 'IntParam',
		Parameter::TYPE_STRING => 'StringParam',
		Parameter::TYPE_TITLE => 'TitleParam',
	);

	/**
	 *
	 *
	 * @param Param $param
	 * @param array $paramDefinitions
	 *
	 * @return array|true
	 */
	public function validate( Param $param, array /* of ParamDefinition */ $paramDefinitions ) {
		return true;
	}

	public function format( Param $param, array /* of ParamDefinition */ $paramDefinitions ) {

	}

}