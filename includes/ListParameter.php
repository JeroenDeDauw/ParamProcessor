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
			if ( $criterion instanceof ListParameterCriterion ) {
				$listCriteria[] = $criterion;
			}
			else {
				$itemCriteria[] = $criterion;
			}		
		}

		parent::construct( $name, $type, $default, $aliases, $itemCriteria );
		
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
	 * Validates all items in the list.
	 * 
	 * @since 0.4
	 * 
	 * @return boolean
	 */
	public function validate() {
		if ( $this->setCount == 0 ) {
			if ( $this->isRequired() ) {
				// TODO: fatal error
				$success = false;
			}
			else {
				$success = true;
				$this->value = $this->default;
			}
		}
		else {
			$this->validateListCriteria( $this->value );
			
			// TODO
			
			foreach ( $this->value as $item ) {
				list( $itemSuccess, $itemHasError ) = $this->validateCriteria( $item );
				
				// TODO
				
				$success = $success && $itemSuccess;
			}
		}
		
		return $success;
	}
	
	/**
	 * 
	 * 
	 * @since 0.4
	 * 
	 * @param array $values
	 */
	protected function validateListCriteria( array $values ) {
		foreach ( $this->getListCriteria() as $listCriterion ) {
			if ( !$listCriterion->validate( $value ) ) {
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
	
}