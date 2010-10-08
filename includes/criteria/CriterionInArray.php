<?php

/**
 * Parameter criterion stating that the value must be in an array.
 * 
 * @since 0.4
 * 
 * @file CriterionInArray.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
class CriterionInArray extends ItemParameterCriterion {
	
	/**
	 * List of allowed values.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	protected $allowedValues;
	
	/**
	 * Constructor.
	 * 
	 * @param mixed $allowedValues
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct();
		
		$args = func_get_args();
		
		if ( count( $args ) > 1 ) {
			$this->allowedValues = $args; 
		}
		elseif ( count( $args ) == 1 )  {
			$this->allowedValues = (array)$args[0];
		}
		else {
			$this->allowedValues = array();
		}
	}
	
	/**
	 * @see ItemParameterCriterion::validate
	 */	
	protected function doValidation( $value, Parameter $parameter, array $parameters ) {
		return in_array( $value, $this->allowedValues );
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getItemErrorMessage( Parameter $parameter ) {
		global $wgLang;
		
		$values = $parameter->getValue();
		$originalCount = count( $this->allowedValues );
		
		if ( $originalCount > 15 ) {
			$allowedValues = array_slice( $this->allowedValues, 0, 13 );
			$omitCount = $originalCount - count( $allowedValues );
			
			return wfMsgExt(
				'validator-error-accepts-only-omitted',
				'parsemag',
				$parameter->getOriginalName(),
				$parameter->getValue(),
				$wgLang->listToText( $allowedValues ),
				$wgLang->formatNum( $omitCount ),
				count( $allowedValues ),
				$omitCount
			);			
		}
		else {
			return wfMsgExt(
				'validator_error_accepts_only',
				'parsemag',
				$parameter->getOriginalName(),
				$wgLang->listToText( $this->allowedValues ),
				count( $this->allowedValues ),
				$parameter->getValue()
			);			
		}
	}
	
	/**
	 * @see ItemParameterCriterion::getListErrorMessage
	 */	
	protected function getListErrorMessage( Parameter $parameter, array $invalidItems, $allInvalid ) {
		global $wgLang;
		return wfMsgExt( 'validator_error_accepts_only', 'parsemag', $wgLang->listToText( $invalidItems ), count( $invalidItems ) );
	}	
	
}