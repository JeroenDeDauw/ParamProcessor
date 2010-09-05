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
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct(  ) {
		parent::__construct();
	}
	
	/**
	 * @see ParameterCriterion::validate
	 */	
	public function validate( $value ) {
		
	}
	
}