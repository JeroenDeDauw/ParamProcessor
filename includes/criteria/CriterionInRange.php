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
class CriterionInRange extends ItemParameterCriterion {
	
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
	 * @see ItemParameterCriterion::validate
	 */	
	protected function doValidation( $value ) {
		if ( ! is_numeric( $value ) ) {
			return false;
		}
		
		$value = (int)$value;
		
		return $value <= $this->upperBound && $value >= $this->lowerBound;		
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getItemErrorMessage( $value ) {
		return wfMsgExt( 'validator_list_error_invalid_range', 'parsemag', $value );
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getListErrorMessage( array $value ) {
		global $wgLang;
		return wfMsgExt( '', 'parsemag', $wgLang->listToText( $value ), count( $value ) );
	}	
	
}