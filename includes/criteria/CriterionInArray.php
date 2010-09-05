<?php

/**
 * Parameter criterion stating that the value must be in an array.
 * 
 * @since 0.4
 * 
 * @file CriterionInArray.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionInArray extends ParameterCriterion {
	
	/**
	 * List of allowed values.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	protected $allowedValues;
	
	/**
	 * Constructor.
	 * 
	 * @param mixed $allowedValues
	 * 
	 * @since 0.4
	 */
	public function __construct( $allowedValues ) {
		parent::__construct();
		
		$this->allowedValues = (array)$allowedValues;
	}
	
	/**
	 * @see ParameterCriterion::validate
	 */	
	public function validate( $value ) {
		return in_array( $value, $this->allowedValues );
	}
	
}