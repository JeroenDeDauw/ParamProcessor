<?php

/**
 * Static class for error handling.
 * 
 * @since 0.4
 * 
 * @file Validator_ErrorHandler.php
 * @ingroup Validator
 * 
 * @author Jeroen De Dauw
 */
final class ValidatorErrorHandler {
	
	/**
	 * @since 0.4
	 * 
	 * @var array of ValidatorError
	 */
	protected static $errors;
	
	/**
	 * Adds a single ValidatorError.
	 * 
	 * @since 0.4
	 * 
	 * @param string $errorMessage
	 * @param integer $severity
	 */
	public static function addError( ValidatorError $error ) {
		self::$errors[$error->element ? $error->element : 'unknown'][] = $error;
	}
	
	/**
	 * Adds a list of ValidatorError.
	 * 
	 * @since 0.4
	 * 
	 * @param array $errors
	 */	
	public static function addErrors( array $errors ) {
		foreach ( $errors as $error ) {
			self::addError( $error );
		}
	}
	
	/**
	 * Returns a list of errors in wikitext.
	 * 
	 * @since 0.4
	 * 
	 * @param integer $minSeverity
	 * 
	 * @return string
	 */
	public static function getErrorList( $minSeverity = ValidatorError::SEVERITY_MINOR ) {
		$elementHtml = array();
		
		if ( count( self::$errors ) == 0 ) {
			return false;
		}
		
		$elements = array_keys( self::$errors );
		natcasesort( $elements );
		
		foreach ( $elements as $element ) {
			$elementErrors = self::getErrorListForElement( $element, $minSeverity ); 
			
			if ( $elementErrors ) {
				$elementHtml[] = $elementErrors;
			}
		}
		
		// TODO: i18n
		return "== Errors ==\n\n" . implode( "\n\n", $elementHtml );
	}
	
	/**
	 * Returns wikitext listing the errors for a single element. 
	 * 
	 * @since 0.4
	 * 
	 * @param string $element
	 * @param integer $minSeverity
	 * 
	 * @return string
	 */	
	public static function getErrorListForElement( $element, $minSeverity = ValidatorError::SEVERITY_MINOR ) {
		$errors = array();
		
		if ( array_key_exists( $element, self::$errors ) ) {
			foreach ( self::$errors[$element] as $error ) {
				if ( $error->severity >= $minSeverity ) {
					$errors[] = $error;
				}
			}			
		}
		
		if ( count( $errors ) > 0 ) {
			$lines = array();
			
			foreach ( $errors as $error ) {
				// TODO: switch on severity
				$lines[] = "* $error->message";
			}
			
			return "=== $element ===\n\n" . implode( "\n", $lines );
		}
		else {
			return false;
		}
	}
	
}