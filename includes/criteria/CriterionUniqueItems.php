<?php

/**
 * Parameter criterion stating that the value must have a certain length.
 * 
 * @since 0.4
 * 
 * @file CriterionHasLength.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionUniqueItems extends ListParameterCriterion {
	
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
	public function validate( ListParameter $parameter ) {
		return count( $parameter->value ) == count( array_unique( $parameter->value ) ); 
	}
	
}