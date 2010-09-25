<?php

/**
 * Item parameter manipulation base class. This is for manipulations
 * that apply to individial values, which can either be the whole value
 * of a non-list parameter, or a single item of a list parameter.
 * 
 * @since 0.4
 * 
 * @file ItemParameterManipulation.php
 * @ingroup Validator
 * @ingroup ParameterManipulations
 * 
 * @author Jeroen De Dauw
 */
abstract class ItemParameterManipulation extends ParameterManipulation {
	
	/**
	 * Manipulate an actual value.
	 * 
	 * @param string $value
	 * @param array $parameters
	 * 
	 * @since 0.4
	 * 
	 * @return mixed
	 */	
	protected abstract function doManipulation( &$value, array &$parameters );	
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * @see ParameterManipulation::isForLists
	 */
	public function isForLists() {
		return false;
	}
	/**
	 * Validate a parameter against the criterion.
	 * 
	 * @param Parameter $parameter
	 * @param array $parameters
	 * 
	 * @since 0.4
	 */	
	public abstract function manipulate( Parameter &$parameter, array &$parameters ) {
		if ( is_array( $parameter->value ) ) {
			foreach ( $parameter->value as &$item ) {
				$this->doManipulation( $item, $parameters );
			}
		}
		else {
			$this->doManipulation( $parameter->value, $parameters );
		}	
	}	
	
}