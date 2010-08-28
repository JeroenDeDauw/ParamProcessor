<?php

abstract class ParserHook {
	
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
	 */
	public function renderTag( $input, array $args, Parser $parser, PPFrame $frame ) {
		$defaultParam = array_shift( $this->getDefaultParameters() );
		
		if ( !is_null( $defaultParam ) ) {
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
	 */
	public function renderFunction() {
		$args = func_get_args();
		
		// No need for the parser...
		array_shift( $args );	
	
		return array( $this->validateAndRender( $args, false ) );
	}
	
	/**
	 * Takes care of validation and rendering, and returns the output.
	 * 
	 * @since 04
	 * 
	 * @param array $arguments
	 * @param boolean $parsed
	 * 
	 * @return string
	 */
	public function validateAndRender( array $arguments, $parsed ) {
		$manager = new ValidatorManager();
		
		if ( $parsed ) {
			$doRender = $manager->manageParsedParameters(
				$arguments,
				$this->getParameterInfo(),
				$this->getDefaultParameters()
			);			
		}
		else {
			$doRender = $manager->manageParameters(
				$arguments,
				$this->getParameterInfo(),
				$this->getDefaultParameters()
			);
		}
		
		if ( $doRender ) {
			$output = $this->render( $manager->getParameters( false ) );
		}
		else {
			$output = $this->handleErrors( $manager );
		}
		
		return $output;
	}
	
	protected function handleErrors( ValidatorManager $manager ) {
		$errorList = $manager->getErrorList();

		$output = '';
		
		if ( $errorList != '' ) {
			$output .= '<br />' . $errorList;
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