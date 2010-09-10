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
class CriterionHasLength extends ItemParameterCriterion {
	
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
	 * @see ItemParameterCriterion::validate
	 */	
	protected function doValidation( $value ) {
		$strlen = strlen( $value );
		return $strlen <= $this->upperBound && $strlen >= $this->lowerBound;
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getItemErrorMessage( Parameter $parameter ) {
		return wfMsgExt( 'validator_error_invalid_argument', 'parsemag', $parameter->value );
	}
	
	/**
	 * @see ItemParameterCriterion::getListErrorMessage
	 */	
	protected function getListErrorMessage( Parameter $parameter, array $invalidItems ) {
		global $wgLang;
		return wfMsgExt( 'validator_list_error_invalid_argument', 'parsemag', $wgLang->listToText( $invalidItems ), count( $invalidItems ) );
	}
	
}