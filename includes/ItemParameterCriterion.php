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
	 * @param Parameter $parameter
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */	
	protected abstract function getItemErrorMessage( Parameter $parameter );
	
	/**
	 * Gets an internationalized error message to construct a ValidationError with
	 * when the criterions validation failed. (for list values)
	 * 
	 * @param Parameter $parameter
	 * @param array $invalidItems
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */	
	protected abstract function getListErrorMessage( Parameter $parameter, array $invalidItems );	
	
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
	 * @param Parameter $parameter
	 * 
	 * @return CriterionValidationResult
	 */
	public function validate( Parameter $parameter ) {
		$result = new CriterionValidationResult();
		
		if ( is_array( $parameter->value ) ) {
			foreach ( $parameter->value as $item ) {
				if ( !$this->doValidation( $item ) ) {
					$result->addInvalidItem( $item );
				}
			}
			
			if ( $result->hasInvalidItems() ) {
				$result->addError(
					new ValidationError( $this->getListErrorMessage( $parameter, $result->getInvalidItems() ) )				
				);
			}
		}
		else {
			if ( !$this->doValidation( $parameter->value ) ) {
				$result->addError(
					new ValidationError( $this->getItemErrorMessage( $parameter ) )
				);
			}
		}
		
		return $result;
	}	
	
}