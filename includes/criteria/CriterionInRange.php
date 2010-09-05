<?php

/**
 * Parameter criterion stating that the value must be in a certain range.
 * 
 * @since 0.4
 * 
 * @file CriterionInRange.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionInRange extends ParameterCriterion {
	
	protected $lowerBound;
	protected $upperBound;	
	
	/**
	 * Constructor.
	 * 
	 * @param integer $lowerBound
	 * @param integer $upperBound
	 * 
	 * @since 0.4
	 */
	public function __construct( $lowerBound, $upperBound ) {
		parent::__construct();
		
		$this->lowerBound = $lowerBound;
		$this->upperBound = $upperBound;		
	}
	
	/**
	 * @see ParameterCriterion::validate
	 */	
	public function validate( $value ) {
		if ( ! is_numeric( $value ) ) {
			return false;
		}
		
		$value = (int)$value;
		
		return $value <= $this->upperBound && $value >= $this->lowerBound;		
	}
	
}