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
class CriterionMatchesRegex extends ItemParameterCriterion {
	
	/**
	 * The pattern to match against.
	 * 
	 * @since 0.4
	 * 
	 * @var string
	 */
	protected $pattern;
	
	/**
	 * Constructor.
	 * 
	 * @param string $pattern
	 * 
	 * @since 0.4
	 */
	public function __construct( $pattern ) {
		parent::__construct();
		
		$this->pattern = $pattern;
	}
	
	/**
	 * @see ItemParameterCriterion::validate
	 */	
	protected function doValidation( $value ) {
		return (bool)preg_match( $this->pattern, $value );
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