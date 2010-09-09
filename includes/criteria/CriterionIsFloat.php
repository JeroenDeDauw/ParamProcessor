<?php

/**
 * Parameter criterion stating that the value must be a float.
 * 
 * @since 0.4
 * 
 * @file CriterionIsFloat.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionIsFloat extends ItemParameterCriterion {
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @see ItemParameterCriterion::validate
	 */	
	protected function doValidation( $value ) {
		return preg_match( '/^\d+(\.\d+)?$/', $value );
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getItemErrorMessage( $value ) {
		return wfMsgExt( 'validator_list_error_invalid_argument', 'parsemag', $value );
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getListErrorMessage( array $value ) {
		global $wgLang;
		return wfMsgExt( '', 'parsemag', $wgLang->listToText( $value ), count( $value ) );
	}	
	
}