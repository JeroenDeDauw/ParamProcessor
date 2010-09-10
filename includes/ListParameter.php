<?php

/**
 * Class for list parameters.
 * 
 * @since 0.4
 * 
 * @file ListParameter.php
 * @ingroup Validator
 * 
 * @author Jeroen De Dauw
 */
class ListParameter extends Parameter {
	
	/**
	 * Indicates if errors in list items should cause the item to be omitted,
	 * versus having the whole list be set to it's default.
	 * 
	 * @since 0.4
	 * 
	 * @var boolean 
	 */
	public static $perItemValidation = true;	
	
	/**
	 * The default delimiter for lists, used when the parameter definition does not specify one.
	 * 
	 * @since 0.4
	 * 
	 * @var string 
	 */
	const DEFAULT_DELIMITER = ',';		
	
	/**
	 * The list delimiter.
	 * 
	 * @since 0.4
	 * 
	 * @var string
	 */
	protected $delimiter;
	
	/**
	 * List of criteria the parameter value as a whole needs to hold against.
	 * 
	 * @since 0.4
	 * 
	 * @var array of ListParameterCriterion
	 */		
	protected $listCriteria;	
	
	/**
	 * Holder for temporary information needed in the itemIsValid callback.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	protected $tempInvalidList;
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 * 
	 * @param string $name
	 * @param string $delimiter
	 * @param mixed $type
	 * @param mixed $default Use null for no default (which makes the parameter required)
	 * @param array $aliases
	 * @param array $criteria
	 */
	public function __construct( $name, $delimiter = ListParameter::DEFAULT_DELIMITER, $type = Parameter::TYPE_STRING,
								 $default = null, array $aliases = array(), array $criteria = array() ) {
		$itemCriteria = array();
		$listCriteria = array();
								 	
		foreach ( $criteria as $criterion ) {
			if ( $criterion->isForLists() ) {
				$listCriteria[] = $criterion;
			}
			else {
				$itemCriteria[] = $criterion;
			}
		}

		parent::__construct( $name, $type, $default, $aliases, $itemCriteria );
		
		$this->delimiter = $delimiter;
		
		$this->cleanCriteria( $listCriteria );
		$this->listCriteria = $listCriteria;
	}
	
	/**
	 * Returns if the parameter is a list or not.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */		
	public function isList() {
		return true;
	}

	/**
	 * Sets the $value to a cleaned value of $originalValue.
	 * 
	 * @since 0.4
	 */
	protected function cleanValue() {
		$this->value = explode( $this->delimiter, $this->originalValue );
		
		if ( $this->lowerCaseValue ) {
			foreach ( $this->value as &$item ) {
				$item = strtolower( $this->value );
			}
		}			
	}	
	
	/**
	 * @see Parameter::validate
	 */
	public function validate() {
		$this->validateListCriteria();
		
		$success = parent::doValidation();
		
		if ( !$success && count( $this->value ) == 0 ) {
			$this->value = (array)$this->default;
		}	
		
		return $success;
		// FIXME: it's possible the list criteria are not satisfied here anymore due to filtering of invalid items.
	}	
	
	/**
	 * 
	 * 
	 * @since 0.4
	 * 
	 * @param array $values
	 */
	protected function validateListCriteria() {
		foreach ( $this->getListCriteria() as $listCriterion ) {
			if ( !$listCriterion->validate( $this->value ) ) {
				$hasError = true;
				
				if ( !self::$accumulateParameterErrors ) {
					break;
				}
			}			
		}
		
		// TODO
	}
	
	/**
	 * Returns the parameter list criteria.
	 * 
	 * @since 0.4
	 * 
	 * @return array of ListParameterCriterion
	 */	
	public function getListCriteria() {
		return $this->listCriteria; 
	}
	
	/**
	 * Handles any validation errors that occured for a single criterion.
	 * 
	 * @since 0.4
	 * 
	 * @param CriterionValidationResult $validationResult
	 */
	protected function handleValidationError( CriterionValidationResult $validationResult ) {
		parent::handleValidationError();
		
		// Filter out the items that have already been found to be invalid.
		if ( $validationResult->hasInvalidItems() ) {
			$this->tempInvalidList = $validationResult->getInvalidItems();
			$this->value = array_filter( $this->value, array( $this, 'itemIsValid' ) );
		}
	}
	
	/**
	 * Returns if an item is valid or not. 
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	protected function itemIsValid( $item ) {
		return !in_array( $item, $this->tempInvalidList );
	}
	
}