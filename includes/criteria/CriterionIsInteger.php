<?php

/**
 * Parameter criterion stating that the value must be an integer.
 * 
 * @since 0.4
 * 
 * @file CriterionIsInteger.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionIsInteger extends ParameterCriterion {
	
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