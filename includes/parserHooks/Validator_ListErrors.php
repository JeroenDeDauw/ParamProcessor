<?php

/**
 * Class for the 'listerrors' parser hooks.
 * 
 * @since 0.4
 * 
 * @file Validator_ListErrors.php
 * @ingroup Validator
 * 
 * @author Jeroen De Dauw
 */
class ValidatorListErrors extends ParserHook {
	
	/**
	 * Array to map the possible values for the 'minseverity' parameter
	 * to their equivalent in the ValidatorError::SEVERITY_ enum.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	protected static $severityMap = array(
		'minor' => ValidatorError::SEVERITY_MINOR,
		'low' => ValidatorError::SEVERITY_LOW,
		'normal' => ValidatorError::SEVERITY_NORMAL,
		'high' => ValidatorError::SEVERITY_HIGH,
		'critical' => ValidatorError::SEVERITY_CRITICAL,
	);
	
	/**
	 * No LST in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */
	public static function staticMagic( array &$magicWords, $langCode ) {
		$className = __CLASS__;
		$instance = new $className();
		return $instance->magic( $magicWords, $langCode );
	}
	
	/**
	 * No LST in pre-5.3 PHP *sigh*.
	 * This is to be refactored as soon as php >=5.3 becomes acceptable.
	 */	
	public static function staticInit( Parser &$wgParser ) {
		$className = __CLASS__;
		$instance = new $className();
		return $instance->init( $wgParser );
	}	
	
	/**
	 * Gets the name of the parser hook.
	 * @see ParserHook::getName
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */
	protected function getName() {
		return 'listerrors';
	}
	
	/**
	 * Returns an array containing the parameter info.
	 * @see ParserHook::getParameterInfo
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */
	protected function getParameterInfo() {
		return array(
			'minseverity' => array(
				'criteria' => array(
					'in_array' => array_keys( self::$severityMap )
				),
				'default' => 'minor'
			)
		);
	}
	
	/**
	 * Returns the list of default parameters.
	 * @see ParserHook::getDefaultParameters
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */
	protected function getDefaultParameters() {
		return array( 'minseverity' );
	}
	
	/**
	 * Renders and returns the output.
	 * @see ParserHook::render
	 * 
	 * @since 0.4
	 * 
	 * @param array $parameters
	 * 
	 * @return string
	 */
	public function render( array $parameters ) {
		$errorList = ValidatorErrorHandler::getErrorList( self::$severityMap[$parameters['minseverity']] );
		
		if ( $errorList ) {
			return $this->parser->recursiveTagParse( $errorList );
		}
		else {
			return '';
		}
	}
	
}