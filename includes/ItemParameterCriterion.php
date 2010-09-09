<?php

/**
 * Item parameter criterion definition class. This is for criteria
 * that apply to individial values, which can either be the whole value
 * of a non-list parameter, or a single item of a list parameter.
 * 
 * @since 0.4
 * 
 * @file ItemParameterCriterion.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
abstract class ItemParameterCriterion extends ParameterCriterion {
	
	/**
	 * Validate a value against the criterion.
	 * 
	 * @param string $value
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */	
	protected abstract function doValidation( $value );
	
	/**
	 * Gets an internationalized error message to construct a ValidationError with
	 * when the criterions validation failed. (for non-list values)
	 * 
	 * @param string $value
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */	
	protected abstract function getItemErrorMessage( $value );
	
	/**
	 * Gets an internationalized error message to construct a ValidationError with
	 * when the criterions validation failed. (for list values)
	 * 
	 * @param array $value
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */	
	protected abstract function getListErrorMessage( array $value );	
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @see ParameterCriterion::isForLists
	 */
	public function isForLists() {
		return false;
	}
	
	/**
	 * Validate the provided value or list of values against the criterion.
	 * 
	 * @since 0.4
	 * 
	 * @param $value
	 * 
	 * @return CriterionValidationResult
	 */
	public function validate( $value ) {
		$result = new CriterionValidationResult();
		
		if ( is_array( $value ) ) {
			foreach ( $value as $item ) {
				if ( !$this->doValidation( $item ) ) {
					$result->addInvalidItem( $item );
				}
			}
			
			if ( $result->hasInvalidItems() ) {
				$result->addError(
					new ValidatorError( $this->getListErrorMessage( $result->getInvalidItems() ) )				
				);
			}
		}
		else {
			if ( !$this->doValidation( $value ) ) {
				$this->getItemErrorMessage( $value );
				
				$result->addError(
					new ValidatorError( $this->getItemErrorMessage( $value ) )
				);
			}
		}
		
		return $result;
	}	
	
}