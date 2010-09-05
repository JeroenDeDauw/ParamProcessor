<?php

/**
 * Parameter criterion stating that the value must not be empty (empty being a string with 0 lentgh).
 * 
 * @since 0.4
 * 
 * @file CriterionNotEmpty.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionNotEmpty extends ParameterCriterion {
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @see ParameterCriterion::validate
	 */	
	public function validate( $value ) {
		return strlen( trim( $value ) ) > 0;
	}
	
}