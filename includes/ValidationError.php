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
class ValidationError {
	
	const SEVERITY_MINOR = 0;
	const SEVERITY_LOW = 1;
	const SEVERITY_NORMAL = 2;
	const SEVERITY_HIGH = 3;
	const SEVERITY_CRITICAL = 4;

	public $message;
	public $severity;
	
	/**
	 * List of 'tags' for the error. This is mainly ment for indicating an error
	 * type, such as 'missing parameter' or 'invalid value', but allows for multiple
	 * such indications.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	public $tags;
	
	/**
	 * Where the error occured.
	 * 
	 * @since 0.4
	 * 
	 * @var mixed: string or false
	 */
	public $element;
	
	/**
	 * @since 0.4
	 * 
	 * @param string $message
	 * @param integer $severity
	 */
	public function __construct( $message, $severity = self::SEVERITY_NORMAL, $element = false, array $tags = array() ) {
		$this->message = $message;
		$this->severity = $severity;
		$this->element = $element;
		$this->tags = $tags;
	}
	
	/**
	 * Returns the error message describing the error.
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}
	
}