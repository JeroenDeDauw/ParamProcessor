<?php

namespace ParamProcessor;

/**
 * Factory for IParamDefinition implementing objects.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.0
 *
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinitionFactory {

	/**
	 * Maps parameter type to handling IParameterDefinition implementing class.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $typeToClass = array();

	/**
	 * Maps parameter type to its associated components.
	 *
	 * @since 1.0
	 *
	 * @var array
	 */
	protected $typeToComponent = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 */
	protected function __construct() {}

	/**
	 * Singleton.
	 *
	 * @since 1.0
	 *
	 * @return ParamDefinitionFactory
	 */
	public static function singleton() {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new static();
			$instance->registerGlobals();
		}

		return $instance;
	}

	/**
	 * Registers the parameter types specified in the global $egParamDefinitions.
	 *
	 * @since 1.0
	 */
	public function registerGlobals() {
		global $egParamDefinitions;

		foreach ( $egParamDefinitions as $type => $data ) {
			if ( is_string( $data ) ) {
				$data = array( 'definition' => $data );
			}

			$this->registerType( $type, $data );
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
	 * - typed-parser:        the parser to use to transform typed PHP values
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
	 * @return boolean Indicates if the type was registered
	 */
	public function registerType( $type, array $data ) {
		if ( array_key_exists( $type, $this->typeToClass ) ) {
			wfWarn( "A handler for the parameter type '$type' has already been registered, the new assignment is ignored." );
			return false;
		}

		$class = array_key_exists( 'definition', $data ) ? $data['definition'] : 'ParamDefinition';
		$this->typeToClass[$type] = $class;

		$defaults = array(
			'string-parser' => 'NullParser',
			'typed-parser' => 'NullParser',
			'validator' => 'NullValidator',
			'validation-callback' => null,
		);

		$this->typeToComponent[$type] = array();

		foreach ( $defaults as $component => $default ) {
			$this->typeToComponent[$type][$component] = array_key_exists( $component, $data ) ? $data[$component] : $default;
		}

		return true;
	}

	/**
	 * Creates a new instance of a IParamDefinition based on the provided type.
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
	 * @throws MWException
	 */
	public function newDefinition( $type, $name, $default, $message, $isList = false ) {
		if ( !array_key_exists( $type, $this->typeToClass ) ) {
			throw new MWException( 'Unknown parameter type "' . $type . '".' );
		}

		$class = $this->typeToClass[$type];

		/**
		 * @var IParamDefinition $definition
		 */
		$definition = new $class(
			$type,
			$name,
			$default,
			$message,
			$isList
		);

		$validator = $this->typeToComponent[$type]['validator'];

		if ( $validator !== 'NullValidator' ) {
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
	 * @since 1.0
	 *
	 * @param string $paramType
	 * @param string $componentType
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function getComponentForType( $paramType, $componentType ) {
		if ( !array_key_exists( $paramType, $this->typeToComponent ) ) {
			throw new MWException( 'Unknown parameter type "' . $paramType . '".' );
		}

		if ( !array_key_exists( $componentType, $this->typeToComponent[$paramType] ) ) {
			throw new MWException( 'Unknown parameter component type "' . $paramType . '".' );
		}

		return $this->typeToComponent[$paramType][$componentType];
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
	public function newDefinitionFromArray( array $param, $getMad = true ) {
		foreach ( array( 'name', 'message' ) as $requiredElement ) {
			if ( !array_key_exists( $requiredElement, $param ) ) {
				if ( $getMad ) {
					throw new MWException( 'Could not construct a ParamDefinition from an array without ' . $requiredElement . ' element' );
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
