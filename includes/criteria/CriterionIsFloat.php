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