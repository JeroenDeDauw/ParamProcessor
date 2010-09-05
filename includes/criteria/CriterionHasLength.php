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
class CriterionHasLength extends ParameterCriterion {
	
	protected $lowerBound;
	protected $upperBound;
	
	/**
	 * Constructor.
	 * 
	 * @param integer $lowerBound
	 * @param mixed $upperBound
	 * 
	 * @since 0.4
	 */
	public function __construct( $lowerBound, $upperBound = false ) {
		parent::__construct();
		
		$this->lowerBound = $lowerBound;
		$this->upperBound = $upperBound === false ? $lowerBound : $upperBound;
	}
	
	/**
	 * @see ParameterCriterion::validate
	 */	
	public function validate( $value ) {
		$strlen = strlen( $value );
		
		if ( $strlen > $this->upperBound ) return false;
		if ( $strlen < $this->lowerBound ) return false;
		
		return true;
	}
	
}