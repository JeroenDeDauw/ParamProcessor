<?php

/**
 * List parameter criterion definition class. This is for criteria
 * that apply to list parameters as a whole instead of to their
 * individual items.
 * 
 * @since 0.4
 * 
 * @file ListParameterCriterion.php
 * @ingroup Validator
 * @ingroup Criteria
 * 
 * @author Jeroen De Dauw
 */
abstract class ListParameterCriterion extends ParameterCriterion {
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		parent::__construct();
	}
}