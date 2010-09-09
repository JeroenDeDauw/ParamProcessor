<?php

/**
 * Parameter criterion that is always true.
 * 
 * @since 0.4
 * 
 * @file CriterionTrue.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionTrue extends ItemParameterCriterion {
	
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
		return true;
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getItemErrorMessage( $value ) {
		return wfMsgExt( '', 'parsemag', $value );
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getListErrorMessage( array $value ) {
		global $wgLang;
		return wfMsgExt( '', 'parsemag', $wgLang->listToText( $value ), count( $value ) );
	}	
	
}