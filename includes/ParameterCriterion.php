<?php

/**
 * Parameter criterion definition class.
 * 
 * @since 0.4
 * 
 * @file ParameterCriterion.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
abstract class ParameterCriterion {
	
	public static $criteria = array();

	/**
	 * Validate a value against the criterion.
	 * 
	 * @param mixed $value
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
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
		$bcMap = array(
			'in_array' => 'CriterionInArray',
			'is_numeric' => 'CriterionIsNumeric',
			'in_range' => 'CriterionInrange',
			'is_float' => 'CriterionIsFloat',
			'is_integer' => 'CriterionIsInteger',
			'not_empty' => 'CriterionNotEmpty',
			'has_length' => 'CriterionHasLength',
			'regex' => 'CriterionMatchesRegex',
			'item_count' => 'CriterionItemCount',
			'unique_items' => 'CriterionUniqueItems',
		);
		
		$className = $bcMap[$name];
		
		switch ( $name ) {
			case 'in_array':
				$criterion = new $className( $definition );
				break;
			case 'in_range': case 'item_count' : case 'has_length' :
				if ( count( $definition ) > 1 ) {
					$criterion = new $className( $definition[0], $definition[1] );
				}
				else {
					$criterion = new $className( $definition[0] );
				}
				break;				
			default:
				$criterion = new $className();
				break;
		}
		
		return $criterion;
	}
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		
	}
	
}