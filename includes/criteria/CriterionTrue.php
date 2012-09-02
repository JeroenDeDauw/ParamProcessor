<?php

/**
 * Parameter criterion that is always true.
 *
 * @deprecated since 1.0, removal in 1.1
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
	protected function doValidation( $value, Parameter $parameter, array $parameters ) {
		return true;
	}
	
	/**
	 * @see ItemParameterCriterion::getItemErrorMessage
	 */	
	protected function getItemErrorMessage( Parameter $parameter ) {
		return '';
	}
	
	/**
	 * @see ItemParameterCriterion::getListErrorMessage
	 */	
	protected function getListErrorMessage( Parameter $parameter, array $invalidItems, $allInvalid ) {
		return '';
	}	
	
}