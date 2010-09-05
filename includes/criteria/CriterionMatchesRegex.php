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
class CriterionMatchesRegex extends ParameterCriterion {
	
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
	 * @see ParameterCriterion::validate
	 */	
	public function validate( $value ) {
		return (bool)preg_match( $this->pattern, $value );
	}
	
}