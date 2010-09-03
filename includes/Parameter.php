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
	
	protected $name;
	
	protected $type;
	
	protected $default;
	
	protected $aliases;
	
	protected $criteria;
	
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
	 * @param string $type
	 * @param mixed $default Use null for no default (which makes the parameter required)
	 * @param array $aliases
	 * @param array $criteria
	 */
	public function __construct( $name, $type = Parameter::TYPE_STRING, $default = null, array $aliases = array(), array $criteria = array() ) {
		
	}
	
	public function setOutputTypes( $outputTypes ) {
		$this->outputTypes = outputTypes;
	}
	
}