<?php

/**
 * Class for parameter validation of a single parser hook or other parameterized construct.
 *
 * @since 0.1
 *
 * @file Validator.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 *
 * TODO: break on fatal errors, such as missing required parameters that are dependencies 
 * TODO: correct invalid parameters in the main loop, as to have correct dependency handling
 * TODO: settings of defaults should happen as a default behaviour that can be overiden by the output format,
 * 		 as it is not wished for all output formats in every case, and now a hacky approach is required there.
 */
class Validator {

	/**
	 * @deprecated TODO: remove
	 * 
	 * @var array Holder for the formatting functions.
	 */
	protected static $mOutputFormats = array(
		'array' => array( 'ValidationFormats', 'format_array' ),
		'list' => array( 'ValidationFormats', 'format_list' ),
		'boolean' => array( 'ValidationFormats', 'format_boolean' ),
		'boolstr' => array( 'ValidationFormats', 'format_boolean_string' ),
		'string' => array( 'ValidationFormats', 'format_string' ),
		'unique_items' => array( 'ValidationFormats', 'format_unique_items' ),
		'filtered_array' => array( 'ValidationFormats', 'format_filtered_array' ),
	);
	
	/**
	 * Array containing the parameters.
	 * 
	 * @since 0.4
	 * 
	 * @var array of Parameter
	 */
	protected $parameters;
	
	/**
	 * List of ValidationError.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Name of the element that's being validated.
	 * 
	 * @since 0.4
	 * 
	 * @var string
	 */
	protected $element;
	
	/**
	 * Constructor.
	 * 
	 * @param srting $element
	 * 
	 * @since 0.4
	 */
	public function __construct( $element = '' ) {
		$this->element = $element;
	}
	
	/**
	 * @deprecated TODO: remove
	 * 
	 * Adds a new output format and the formatting function that should validate values of this type.
	 * You can use this function to override existing criteria type handlers.
	 *
	 * @param string $formatName The name of the format.
	 * @param array $functionName The functions location. If it's a global function, only the name,
	 * if it's in a class, first the class name, then the method name.
	 */
	public static function addOutputFormat( $formatName, array $functionName ) {
		self::$mOutputFormats[strtolower( $formatName )] = $functionName;
	}	
	
	/**
	 * Determines the names and values of all parameters. Also takes care of default parameters. 
	 * After that the resulting parameter list is passed to Validator::setParameters
	 * 
	 * @since 0.4
	 * 
	 * @param array $rawParams
	 * @param array $parameterInfo
	 * @param array $defaultParams
	 * @param boolean $toLower Indicates if the parameter values should be put to lower case. Defaults to true.
	 */
	public function setFunctionParams( array $rawParams, array $parameterInfo, array $defaultParams = array(), $toLower = true ) {
		$this->cleanParameterInfo( $parameterInfo );
		
		$parameters = array();

		$nr = 0;
		$defaultNr = 0;
		
		foreach ( $rawParams as $arg ) {
			// Only take into account strings. If the value is not a string,
			// it is not a raw parameter, and can not be parsed correctly in all cases.
			if ( is_string( $arg ) ) {
				$parts = explode( '=', $arg, 2 );
				
				// If there is only one part, no parameter name is provided, so try default parameter assignment.
				if ( count( $parts ) == 1 ) {
					// Default parameter assignment is only possible when there are default parameters!
					if ( count( $defaultParams ) > 0 ) {
						$defaultParam = strtolower( array_shift( $defaultParams ) );
						
						$parameters[$defaultParam] = array(
							'original-value' => trim( $parts[0] ),
							'default' => $defaultNr,
							'position' => $nr
						);
						$defaultNr++;
					}
					else {
						// It might be nice to have some sort of warning or error here, as the value is simply ignored.
					}
				} else {
					$paramName = trim( strtolower( $parts[0] ) );
					
					$parameters[$paramName] = array(
						'original-value' => trim( $parts[1] ),
						'default' => false,
						'position' => $nr
					);
					
					// Let's not be evil, and remove the used parameter name from the default parameter list.
					// This code is basically a remove array element by value algorithm.
					$newDefaults = array();
					
					foreach( $defaultParams as $defaultParam ) {
						if ( $defaultParam != $paramName ) $newDefaults[] = $defaultParam;
					}
					
					$defaultParams = $newDefaults;
				}
			}
			$nr++;
		}	

		$this->setParameters( $parameters, $parameterInfo, false );
	}
	
	/**
	 * Loops through a list of provided parameters, resolves aliasing and stores errors
	 * for unknown parameters and optionally for parameter overriding.
	 * 
	 * @param array $parameters Parameter name as key, parameter value as value
	 * @param array $parameterInfo Main parameter name as key, parameter meta data as valu
	 * @param boolean $toLower Indicates if the parameter values should be put to lower case. Defaults to true.
	 */
	public function setParameters( array $parameters, array $parameterInfo, $toLower = true ) {
		$this->cleanParameterInfo( $parameterInfo );
		
		$this->parameters = $parameterInfo;
		
		// Loop through all the user provided parameters, and destinguise between those that are allowed and those that are not.
		foreach ( $parameters as $paramName => $paramData ) {
			$paramName = trim( strtolower( $paramName ) );
			
			// Attempt to get the main parameter name (takes care of aliases).
			$mainName = self::getMainParamName( $paramName );

			// If the parameter is found in the list of allowed ones, add it to the $mParameters array.
			if ( $mainName ) {
				// If the valueis an array, this means it has been procesed in parseAndSetParams already.
				// If it is not, setParameters was called directly with an array of string parameter values.
				if ( is_array( $paramData ) ) {
					$this->parameters[$mainName]->setUserValue( $paramName, $paramData['original-value'] ); 
				}
				else {
					if ( is_string( $paramData ) ) {
						$paramData = trim( $paramData );
					}
					
					$this->parameters[$mainName]->setUserValue( $paramName, $paramData ); 
				}
			
			}
			else { // If the parameter is not found in the list of allowed ones, add an item to the $this->mErrors array.
				$this->registerNewError(
					wfMsgExt(
						'validator_error_unknown_argument',
						'parsemag',
						$paramName
					),
					'unknown'		
				);		
			}		
		}
	}
	
	/**
	 * Registers an error.
	 * 
	 * @since 0.4
	 * 
	 * @param string $message
	 * @param mixed $tags string or array
	 * @param integer $severity
	 */
	protected function registerNewError( $message, $tags = array(), $severity = ValidationError::SEVERITY_NORMAL ) {
		$this->registerError(
			new ValidationError(
				$message,
				$severity,
				$this->element,
				(array)$tags
			)
		);
	}
	
	/**
	 * Registers an error.
	 * 
	 * @since 0.4
	 * 
	 * @param ValidationError $error
	 */
	protected function registerError( ValidationError $error ) {
		$this->errors[] = $error;
		ValidationErrorHandler::addError( $error );		
	}
	
	/**
	 * Ensures all elements of the array are Parameter objects.
	 * 
	 * @since 0.4
	 * 
	 * @param array $paramInfo
	 */
	protected function cleanParameterInfo( array &$paramInfo ) {
		foreach ( $paramInfo as $key => &$parameter ) {
			$parameter = $parameter instanceof Parameter ? $parameter : Parameter::newFromArray( $key, $parameter );
		}
	}	
	
	/**
	 * Returns the main parameter name for a given parameter or alias, or false
	 * when it is not recognized as main parameter or alias.
	 *
	 * @param string $paramName
	 *
	 * @return string or false
	 */
	protected function getMainParamName( $paramName ) {
		$result = false;

		if ( array_key_exists( $paramName, $this->parameters ) ) {
			$result = $paramName;
		}
		else {
			foreach ( $this->parameters as $name => $parameter ) {
				if ( $parameter->hasAlias( $paramName ) ) {
					$result = $name;
					break;
				}
			}
		}

		return $result;
	}
	
	/**
	 * Validates all the parameters (but aborts when a fatal error occurs).
	 * 
	 * @since 0.4
	 */
	public function validateParameters() {
		$dependencyList = array();
		
		foreach ( $this->parameters as $paramName => $parameter ) {
			$dependencyList[$paramName] = $parameter->dependencies;
		}
		
		$sorter = new TopologicalSort( $dependencyList, true );
		$orderedParameters = $sorter->doSort();

		foreach ( $orderedParameters as $paramName ) {
			$parameter = $this->parameters[$paramName];
			
			if ( !$parameter->validate() ) {
				foreach ( $parameter->getErrors() as $error ) {
					$this->registerError( $error );
				}
			}
		}
	}
	
	/**
	 * Applies the output formats to all parameters.
	 * 
	 * @param string $name
	 */
	public function formatParameters() {
		foreach ( $this->parameters as $parameter ) {
			foreach ( $parameter->outputTypes as $outputType ) {
				$outputType[0] = strtolower( $outputType[0] );
				if ( array_key_exists( $outputType[0], self::$mOutputFormats ) ) {
					$parameters = array( &$parameter->value, $parameter->getName(), $this->parameters );
					$name = array_shift( $outputType );
					$parameters = array_merge( $parameters, $outputType );
					call_user_func_array( self::$mOutputFormats[$name], $parameters );
				}
				else {
					throw new Exception( 'There is no formatting function for output format ' . $outputType[0] );
				}				
			}			
		}
	}

	/**
	 * Returns the parameters.
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}
	
	/**
	 * Returns an associative array with the parameter names as key and their
	 * correspinding values as value.
	 * 
	 * @since 0.4
	 * 
	 * @return array
	 */
	public function getParameterValues() {
		$parameters = array();
		
		foreach ( $this->parameters as $parameter ) {
			$parameters[$parameter->getName()] = $parameter->getValue(); 
		}
		
		return $parameters;
	}
	
	/**
	 * Returns the errors.
	 *
	 * @return array of ValidationError
	 */
	public function getErrors() {
		return $this->errors;
	}
	
	/**
	 * Returns if there where any errors during validation. 
	 * 
	 * @return boolean
	 */
	public function hasErrors() {
		return count( $this->errors ) > 0;
	}
	
	/**
	 * Returns wether there are any fatal errors. Fatal errors are either missing or invalid required parameters,
	 * or simply any sort of error when the validation level is equal to (or bigger then) Validator_ERRORS_STRICT.
	 * 
	 * @return boolean
	 */
	public function hasFatalError() {
		$has = false;
		
		foreach ( $this->errors as $error ) {
			if ( $error->severity >= ValidationError::SEVERITY_CRITICAL ) {
				$has = true;
				break;
			}
		}
		
		return $has;
	}	
	
}