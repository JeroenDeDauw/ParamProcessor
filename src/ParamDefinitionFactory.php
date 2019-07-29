<?php

namespace ParamProcessor;

use Exception;
use OutOfBoundsException;
use ValueParsers\NullParser;
use ValueValidators\NullValidator;

/**
 * Factory for ParamDefinition implementing objects.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinitionFactory {

	/**
	 * Maps parameter type to handling ParameterDefinition implementing class.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	private $typeToClass = [];

	/**
	 * Maps parameter type to its associated components.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	private $typeToComponent = [];

	/**
	 * Returns a ParamDefinitionFactory that already has the core parameter types (@see ParameterTypes) registered.
	 *
	 * @since 1.6
	 */
	public static function newDefault(): self {
		$instance = new self();

		foreach ( ParameterTypes::getCoreTypes() as $type => $data ) {
			$instance->registerType( $type, $data );
		}

		return $instance;
	}

	/**
	 * @deprecated since 1.0
	 */
	public static function singleton(): self {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new self();
			$instance->registerGlobals();
		}

		return $instance;
	}

	/**
	 * Registers the parameter types specified in the global $wgParamDefinitions.
	 * @deprecated since 1.6
	 */
	public function registerGlobals() {
		if ( array_key_exists( 'wgParamDefinitions', $GLOBALS ) ) {
			foreach ( $GLOBALS['wgParamDefinitions'] as $type => $data ) {
				if ( is_string( $data ) ) {
					$data = [ 'definition' => $data ];
				}

				$this->registerType( $type, $data );
			}
		}
	}

	/**
	 * Registers a parameter type.
	 *
	 * The type is specified as a string identifier for the type, ie 'boolean',
	 * and an array containing further data. This data currently includes:
	 *
	 * - string-parser:       the parser to use to transform string values
	 *                        This class needs to implement ValueParser. Default: NullParser
	 * - typed-parser:        DEPRECATED since 1.6 - the parser to use to transform typed PHP values
	 *                        This class needs to implement ValueParser. Default: NullParser
	 * - validator:           the validation object to use
	 *                        This class needs to implement ValueValidator. Default: NullValidator
	 * - validation-callback  a callback to use for validation, called before the ValueValidator
	 *                        This callback needs to return a boolean indicating validity.
	 *
	 * @since 1.0
	 *
	 * @param string $type
	 * @param array $data
	 *
	 * @return boolean DEPRECATED since 1.6 - Indicates if the type was registered
	 */
	public function registerType( $type, array $data ) {
		if ( array_key_exists( $type, $this->typeToClass ) ) {
			return false;
		}

		// Deprecated: definition key
		$class = array_key_exists( 'definition', $data ) ? $data['definition'] : ParamDefinition::class;
		$this->typeToClass[$type] = $class;

		$defaults = [
			'string-parser' => NullParser::class,
			'typed-parser' => NullParser::class,
			'validator' => NullValidator::class,
			'validation-callback' => null,
		];

		$this->typeToComponent[$type] = [];

		foreach ( $defaults as $component => $default ) {
			$this->typeToComponent[$type][$component] = array_key_exists( $component, $data ) ? $data[$component] : $default;
		}

		return true;
	}

	/**
	 * Creates a new instance of a ParamDefinition based on the provided type.
	 *
	 * @param string $type
	 * @param string $name
	 * @param mixed $default
	 * @param string $message
	 * @param boolean $isList
	 *
	 * @return ParamDefinition
	 * @throws OutOfBoundsException
	 */
	public function newDefinition( string $type, string $name, $default, string $message, bool $isList = false ): ParamDefinition {
		if ( !array_key_exists( $type, $this->typeToClass ) ) {
			throw new OutOfBoundsException( 'Unknown parameter type "' . $type . '".' );
		}

		$class = $this->typeToClass[$type];

		/**
		 * @var ParamDefinition $definition
		 */
		$definition = new $class(
			$type,
			$name,
			$default,
			$message,
			$isList
		);

		$validator = $this->typeToComponent[$type]['validator'];

		if ( $validator !== NullValidator::class ) {
			$definition->setValueValidator( new $validator() );
		}

		$validationCallback = $this->typeToComponent[$type]['validation-callback'];

		if ( $validationCallback !== null ) {
			$definition->setValidationCallback( $validationCallback );
		}

		return $definition;
	}

	/**
	 * Returns the specified component for the provided parameter type.
	 * This method is likely to change in the future in a compat breaking way.
	 *
	 * @param string $paramType
	 * @param string $componentType
	 *
	 * @throws Exception
	 * @return mixed
	 */
	public function getComponentForType( $paramType, $componentType ) {
		if ( !array_key_exists( $paramType, $this->typeToComponent ) ) {
			throw new Exception( 'Unknown parameter type "' . $paramType . '".' );
		}

		if ( !array_key_exists( $componentType, $this->typeToComponent[$paramType] ) ) {
			throw new Exception( 'Unknown parameter component type "' . $paramType . '".' );
		}

		return $this->typeToComponent[$paramType][$componentType];
	}

	/**
	 * @param array $param
	 * @param bool $getMad DEPRECATED since 1.6
	 *
	 * @return ParamDefinition|false
	 * @throws Exception
	 */
	public function newDefinitionFromArray( array $param, $getMad = true ) {
		foreach ( [ 'name', 'message' ] as $requiredElement ) {
			if ( !array_key_exists( $requiredElement, $param ) ) {
				if ( $getMad ) {
					throw new Exception( 'Could not construct a ParamDefinition from an array without ' . $requiredElement . ' element' );
				}

				return false;
			}
		}

		$parameter = $this->newDefinition(
			array_key_exists( 'type', $param ) ? $param['type'] : 'string',
			$param['name'],
			array_key_exists( 'default', $param ) ? $param['default'] : null,
			$param['message'],
			array_key_exists( 'islist', $param ) ? $param['islist'] : false
		);

		$parameter->setArrayValues( $param );

		return $parameter;
	}

}
