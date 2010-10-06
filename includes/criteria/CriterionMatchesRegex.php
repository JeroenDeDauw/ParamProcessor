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
	protected function doValidation( $value, Parameter $parameter, array &$parameters ) {
		return (bool)preg_match( $this->pattern, $value );
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getItemErrorMessage( Parameter $parameter ) {
		return wfMsgExt( 'validator_error_invalid_argument', 'parsemag', $parameter->value, $parameter->getOriginalName() );
	}
	
	/**
	 * @see ItemParameterCriterion::getListErrorMessage
	 */	
	protected function getListErrorMessage( Parameter $parameter, array $invalidItems ) {
		global $wgLang;
		return wfMsgExt( 'validator_list_error_invalid_argument', 'parsemag', $wgLang->listToText( $invalidItems ), count( $invalidItems ) );
	}	
	
}