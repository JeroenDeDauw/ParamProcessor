<?php

/**
 * Class for out of the box parser hook functionality inetgrated with the validation
 * provided by Validator.
 *
 * @since 0.4
 *
 * @file ParserHook.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */
abstract class ParserHook {
	
	/**
	 * @since 0.4
	 * 
	 * @var Validator
	 */	
	protected $validator;
	
	/**
	 * @since 0.4
	 * 
	 * @var Parser
	 */
	protected $parser;
	
	/**
	 * Gets the name of the parser hook.
	 * 
	 * @since 0.4
	 * 
	 * @return string
	 */
	protected abstract function getName();
	
	/**
	 * Renders and returns the output.
	 * 
	 * @since 0.4
	 * 
	 * @param array $parameters
	 * 
	 * @return string
	 */
	protected abstract function render( array $parameters );	
	
	/**
	 * Constructor.
	 * 
	 * @since 0.4
	 */
	public function __construct() {
		
	}
	
	/**
	 * Function to hook up the coordinate rendering functions to the parser.
	 * 
	 * @since 0.4
	 * 
	 * @param Parser $wgParser
	 * 
	 * @return true
	 */
	public function init( Parser &$wgParser ) {
		$wgParser->setHook( $this->getName(), array( $this, 'renderTag' ) );
		$wgParser->setFunctionHook( $this->getName(), array( $this, 'renderFunction' ) );
		
		return true;
	}
	
	/**
	 * Function to add the magic word in pre MW 1.16.
	 * 
	 * @since 0.4
	 * 
	 * @param array $magicWords
	 * @param string $langCode
	 * 
	 * @return true
	 */
	public function magic( array &$magicWords, $langCode ) {
		$magicWords[$this->getName()] = array( 0, $this->getName() );
		
		return true;
	}	
	
	/**
	 * Handler for rendering the tag hook.
	 * 
	 * @since 0.4
	 * 
	 * @param minxed $input string or null
	 * @param array $args
	 * @param Parser $parser
	 * @param PPFrame $frame
	 * 
	 * @return string
	 */
	public function renderTag( $input, array $args, Parser $parser, PPFrame $frame ) {
		$this->parser = $parser;
		
		$defaultParam = array_shift( $this->getDefaultParameters() );

		// If there is a first default parameter, set the tag contents as it's value.
		if ( !is_null( $defaultParam ) && !is_null( $input ) ) {
			$args[$defaultParam] = $input;
		}
		
		return $this->validateAndRender( $args, true );
	}
	
	/**
	 * Handler for rendering the function hook.
	 * 
	 * @since 0.4
	 * 
	 * @param Parser $parser
	 * ... further arguments ...
	 * 
	 * @return array
	 */
	public function renderFunction() {
		$args = func_get_args();
		
		$this->parser = array_shift( $args );	
	
		return array( $this->validateAndRender( $args, false ) );
	}
	
	/**
	 * Takes care of validation and rendering, and returns the output.
	 * 
	 * @since 0.4
	 * 
	 * @param array $arguments
	 * @param boolean $parsed
	 * 
	 * @return string
	 */
	public function validateAndRender( array $arguments, $parsed ) {
		global $egValidatorErrorLevel;
		
		$this->validator = new Validator( $this->getName() );
		
		if ( $parsed ) {
			$this->validator->setParameters( $arguments, $this->getParameterInfo() );
		}
		else {
			$this->validator->parseAndSetParams( $arguments, $this->getParameterInfo(), $this->getDefaultParameters() );
		}
		
		$this->validator->validateAndFormatParameters();
		
		if ( $this->validator->hasErrors() && $egValidatorErrorLevel < Validator_ERRORS_STRICT ) {
			$this->validator->correctInvalidParams();
		}
		
		if ( $this->validator->hasFatalError() ) {
			// TODO
			$output = 'Demo: fatal error';
		}
		else {
			$output = $this->render( $this->validator->getValidParams( false ) );
		}
		
		return $output;
	}
	
	/**
	 * Returns an array containing the parameter info.
	 * Override in deriving classes to add parameter info.
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */
	protected function getParameterInfo() {
		return array();
	}
	
	/**
	 * Returns the list of default parameters.
	 * Override in deriving classes to add default parameters.
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */
	protected function getDefaultParameters() {
		return array();
	}	
	
}