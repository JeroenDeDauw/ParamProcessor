<?php

/**
 * Error class.
 * 
 * @since 0.4
 * 
 * @file Validator_Error.php
 * @ingroup Validator
 * 
 * @author Jeroen De Dauw
 */
class ValidatorError {
	
	const SEVERITY_MINOR = 0;
	const SEVERITY_LOW = 1;
	const SEVERITY_NORMAL = 2;
	const SEVERITY_HIGH = 3;
	const SEVERITY_CRITICAL = 4;

	public $message;
	public $severity;
	
	/**
	 * Where the error occured.
	 * 
	 * @since 0.4
	 * 
	 * @var mixed: string or false
	 */
	protected $element;
	
	/**
	 * @since 0.4
	 * 
	 * @param string $message
	 * @param integer $severity
	 */
	public function __construct( $message, $severity = ValidatorError::SEVERITY_NORMAL, $element = false ) {
		$this->message = $message;
		$this->severity = $severity;
		$this->element = $element;
	}
	
}