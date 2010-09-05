<?php

/**
 * Parameter criterion stating that the value must match a regex.
 * 
 * @since 0.4
 * 
 * @file CriterionMatchesRegex.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionMatchesRegex extends ParameterCriterion {
	
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