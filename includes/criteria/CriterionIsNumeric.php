<?php

/**
 * Parameter criterion stating that the value must be a number.
 * 
 * @since 0.4
 * 
 * @file CriterionIsNumeric.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionIsNumeric extends ParameterCriterion {
	
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
		return is_numeric( $value );
	}
	
}