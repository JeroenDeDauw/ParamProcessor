<?php

/**
 * Parameter criterion definition class.
 * 
 * @since 0.4
 * 
 * @file ParameterCriterion.php
 * @ingroup Validator
 * 
 * @author Jeroen De Dauw
 */
abstract class ParameterCriterion {
	
	public static $criteria = array();

	/**
	 * Validate a value against the criterion.
	 * 
	 * @param string $value
	 * 
	 * @since 0.4
	 */	
	public abstract function validate( $value );
	
	/**
	 * Returns a new instance of ParameterCriterion by converting an element of a Validator 3.x-style criteria array definition.
	 * Note: this method is for backward compatibility and should not be used in new code.
	 * 
	 * @since 0.4
	 * 
	 * @param string $name
	 * @param array $definition
	 * 
	 * @return ParameterCriterion
	 */
	public static function newFromArray( $name, array $definition ) {
		
	}
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		
	}
	
	protected function getValidationFunction() {
		
	}
	
}