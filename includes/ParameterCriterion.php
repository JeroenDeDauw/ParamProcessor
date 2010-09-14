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
	
	/**
	 * Validate a parameter against the criterion.
	 * 
	 * @param Parameter $parameter
	 * 
	 * @since 0.4
	 * 
	 * @return CriterionValidationResult
	 */	
	public abstract function validate( Parameter $parameter );
	
	/**
	 * Returns if the criterion applies to lists as a whole.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */	
	public abstract function isForLists();		
	
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
			'in_range' => 'CriterionInRange',
			'is_float' => 'CriterionIsFloat',
			'is_integer' => 'CriterionIsInteger',
			'not_empty' => 'CriterionNotEmpty',
			'has_length' => 'CriterionHasLength',
			'regex' => 'CriterionMatchesRegex',
			'item_count' => 'CriterionItemCount',
			'unique_items' => 'CriterionUniqueItems',
		);
		
		$className = array_key_exists( $name, $bcMap ) ? $bcMap[$name] : 'CriterionTrue';
		
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