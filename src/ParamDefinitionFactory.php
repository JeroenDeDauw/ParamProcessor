<?php

namespace ParamProcessor;

use Exception;
use OutOfBoundsException;
use ParamProcessor\PackagePrivate\ParamType;
use ValueValidators\NullValidator;

/**
 * Factory for ParamDefinition implementing objects.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinitionFactory {

	private $types;

	/**
	 * @since 1.8
	 */
	public function __construct( ParameterTypes $types = null ) {
		$this->types = $types ?? new ParameterTypes();
	}

	/**
	 * Returns a ParamDefinitionFactory that already has the core parameter types (@see ParameterTypes) registered.
	 *
	 * @since 1.6
	 */
	public static function newDefault(): self {
		return new self( ParameterTypes::newCoreTypes() );
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
		if ( $this->types->hasType( $type ) ) {
			return false;
		}

		$this->types->addType( $type, $data );

		return true;
	}

	/**
	 * Creates a new instance of a ParamDefinition based on the provided type.
	 *
	 * @param string $typeName
	 * @param string $name
	 * @param mixed $default
	 * @param string $message
	 * @param boolean $isList
	 *
	 * @return ParamDefinition
	 * @throws OutOfBoundsException
	 */
	public function newDefinition( string $typeName, string $name, $default, string $message, bool $isList = false ): ParamDefinition {
		if ( !$this->types->hasType( $typeName ) ) {
			throw new OutOfBoundsException( 'Unknown parameter type "' . $typeName . '".' );
		}

		$type = $this->types->getType( $typeName );
		$class = $type->getClassName();

		/**
		 * @var ParamDefinition $definition
		 */
		$definition = new $class(
			$typeName,
			$name,
			$default,
			$message,
			$isList
		);

		$validator = $type->getValidatorClass();

		if ( $validator !== NullValidator::class ) {
			$definition->setValueValidator( new $validator() );
		}

		$validationCallback = $type->getValidationCallback();

		if ( $validationCallback !== null ) {
			$definition->setValidationCallback( $validationCallback );
		}

		return $definition;
	}

	/**
	 * Package private
	 */
	public function getType( string $typeName ): ParamType {
		return $this->types->getType( $typeName );
	}

	/**
	 * @param array $definitionArray
	 * @param bool $getMad DEPRECATED since 1.6
	 *
	 * @return ParamDefinition|false
	 * @throws Exception
	 */
	public function newDefinitionFromArray( array $definitionArray, $getMad = true ) {
		foreach ( [ 'name', 'message' ] as $requiredElement ) {
			if ( !array_key_exists( $requiredElement, $definitionArray ) ) {
				if ( $getMad ) {
					throw new Exception( 'Could not construct a ParamDefinition from an array without ' . $requiredElement . ' element' );
				}

				return false;
			}
		}

		$definition = $this->newDefinition(
			array_key_exists( 'type', $definitionArray ) ? $definitionArray['type'] : 'string',
			$definitionArray['name'],
			array_key_exists( 'default', $definitionArray ) ? $definitionArray['default'] : null,
			$definitionArray['message'],
			array_key_exists( 'islist', $definitionArray ) ? $definitionArray['islist'] : false
		);

		$definition->setArrayValues( $definitionArray );

		return $definition;
	}

	/**
	 * @since 1.9
	 *
	 * @param array $definitionArrays Each element must either be
	 * - A definition array with "name" key
	 * - A name key pointing to a definition array
	 * - A ParamDefinition instance (discouraged)
	 *
	 * @return ParamDefinition[]
	 * @throws Exception
	 */
	public function newDefinitionsFromArrays( array $definitionArrays ): array {
		$cleanList = [];

		foreach ( $definitionArrays as $key => $definitionArray ) {
			if ( is_array( $definitionArray ) ) {
				if ( !array_key_exists( 'name', $definitionArray ) && is_string( $key ) ) {
					$definitionArray['name'] = $key;
				}

				$definitionArray = $this->newDefinitionFromArray( $definitionArray );
			}

			if ( !( $definitionArray instanceof IParamDefinition ) ) {
				throw new Exception( 'Parameter definition not an instance of IParamDefinition' );
			}

			$cleanList[$definitionArray->getName()] = $definitionArray;
		}

		return $cleanList;
	}

}
