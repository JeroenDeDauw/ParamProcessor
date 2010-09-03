<?php

/**
 * Parameter definition class.
 * 
 * @since 0.4
 * 
 * @file Parameter.php
 * @ingroup Validator
 * 
 * @author Jeroen De Dauw
 */
class Parameter {
	
	const TYPE_STRING = 'string';
	const TYPE_NUMBER = 'number';
	const TYPE_INTEGER = 'integer';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_CHAR = 'char';
	
	/**
	 * The main name of the parameter.
	 * 
	 * @since 0.4
	 * 
	 * @var string
	 */
	protected $name;
	
	/**
	 * The type of the parameter. Is either a string of the Parameter::TYPE_ enum,
	 * or an array with such a string as first element, and optionaly a delimiter
	 * as second element for list types.
	 * 
	 * @since 0.4
	 * 
	 * @var mixed
	 */
	protected $type;
	
	/**
	 * The default value for the parameter, or null when the parameter is required.
	 * 
	 * @since 0.4
	 * 
	 * @var mixed
	 */
	protected $default;
	
	/**
	 * List of aliases for the parameter name.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */	
	protected $aliases;
	
	/**
	 * List of criteria the parameter value needs to hold against.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */		
	protected $criteria;
	
	/**
	 * List of formatting functions to shape the final form of the parameter value. 
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */			
	protected $outputTypes;
	
	/**
	 * Returns a new instance of Parameter by converting a Validator 3.x-style parameter array definition.
	 * 
	 * @since 0.4
	 * 
	 * @param string $name
	 * @param array $definition
	 * 
	 * @return Parameter
	 */
	public static function newFromArray( $name, array $definition ) {
		if ( array_key_exists( 'type', $definition ) ) {
			if ( is_array( $definition['type'] ) ) {
				if ( count( $definition['type'] ) > 1 ) {
					if ( count( $definition['type'] ) > 2 ) {
						$type = array( $definition['type'][0], $definition['type'][2] );
					}
					else {
						$type = array( $definition['type'][0] );
					}
				}
				else {
					$type = $definition['type'][0];
				}
			}
			else {
				$type = $definition['type'];
			}
		}
		else {
			$type = 'string';
		}
		
		if ( array_key_exists( 'required', $definition ) && $definition['required'] ) {
			$default = null;
		}
		else {
			$default = array_key_exists( 'default', $definition ) ? $definition['default'] : '';
		}
		
		$parameter = new Parameter(
			$name,
			$type,
			$default,
			array_key_exists( 'aliases', $definition ) ? $definition['aliases'] : array(),
			array_key_exists( 'criteria', $definition ) ? $definition['criteria'] : array()
		);
		
		if ( array_key_exists( 'output-types', $definition ) ) {
			$types = array();
			
			for ( $i = 0, $c = count( $definition['output-types'] ); $i < $c; $i++ ) {
				if ( ! is_array( $definition['output-types'][$i] ) ) {
					$definition['output-types'][$i] = array( $definition['output-types'][$i] );
				}
				
				$types[$name] = $definition['output-types'][$i];
			}
			
			$parameter->setOutputTypes( $types );
		}
		elseif ( array_key_exists( 'output-type', $definition ) ) {
			if ( ! is_array( $definition['output-type'] ) ) {
				$definition['output-type'] = array( $definition['output-type'] );
			}
			
			$parameter->setOutputTypes( array( $name => $definition['output-type'] ) );
		}		
		
		return $parameter;
	}
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 * 
	 * @param string $name
	 * @param mixed $type
	 * @param mixed $default Use null for no default (which makes the parameter required)
	 * @param array $aliases
	 * @param array $criteria
	 */
	public function __construct( $name, $type = Parameter::TYPE_STRING, $default = null, array $aliases = array(), array $criteria = array() ) {
		$this->name = $name;
		$this->type = $type;
		$this->default = $default;
		$this->aliases = $aliases;
		$this->criteria = $criteria;
	}
	
	/**
	 * Sets the output types to the provided value.
	 * 
	 * @since 0.4
	 * 
	 * @param array $outputTypes
	 */
	public function setOutputTypes( array $outputTypes ) {
		$this->outputTypes = outputTypes;
	}
	
	/**
	 * Returns the parameters main name.
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */			
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Returns if the parameter is a required one or not.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */		
	public function isRequired() {
		return is_null( $this->default );
	}
	
	/**
	 * Returns the parameter criteria.
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */	
	public function getCriteria() {
		// TODO: type criteria resolving
		return $this->criteria; 
	}
	
	/**
	 * Returns the parameter name aliases.
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */
	public function getAliases() {
		return $this->aliases;
	}
	
}