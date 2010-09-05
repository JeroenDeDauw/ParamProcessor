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
		parent::construct( $name, $type, $default, $aliases, $criteria );
		$this->delimiter = $delimiter;
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
	
}