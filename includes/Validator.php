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
	 * @var boolean Indicates whether parameters not found in the criteria list
	 * should be stored in case they are not accepted. The default is false.
	 */
	public static $storeUnknownParameters = false;

	/**
	 * @var boolean Indicates whether parameters not found in the criteria list
	 * should be stored in case they are not accepted. The default is false.
	 */
	public static $accumulateParameterErrors = false;
	
	/**
	 * @var boolean Indicates whether parameters that are provided more then once 
	 * should be accepted, and use the first provided value, or not, and generate an error.
	 */
	public static $acceptOverriding = false;
	
	/**
	 * @var boolean Indicates if errors in list items should cause the item to be omitted,
	 * versus having the whole list be set to it's default.
	 */
	public static $perItemValidation = true;

	/**
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
	 * Array containing parameter definitions.
	 * 
	 * @since 0.4
	 * 
	 * @var array of Parameter
	 */
	protected $parameterInfo;
	
	/**
	 * An array initially containing the user provided values. Adittional data about
	 * the validation and formatting processes gets added later on, and so stays 
	 * available for validation and formatting of other parameters.
	 * 
	 * original-value
	 * default
	 * position
	 * original-name
	 * formatted-value
	 * 
	 * @var associative array
	 */
	protected $mParameters = array();
	
	/**
	 * Arrays for holding the (main) names of valid, invalid and unknown parameters. 
	 */
	protected $mValidParams = array();
	protected $mInvalidParams = array();
	protected $mUnknownParams = array();
	
	/**
	 * List of ValidatorError.
	 * 
	 * @since 0.4
	 * 
	 * @var array
	 */
	protected $errors = array();

	/**
	 * 
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
	 * Registers an error.
	 * 
	 * @param string $message
	 * @param mixed $tags string or array
	 * @param integer $severity
	 */
	protected function registerError( $message, $tags = array(), $severity = ValidatorError::SEVERITY_NORMAL ) {
		$error = new ValidatorError(
			$message,
			$severity,
			$this->element,
			(array)$tags
		);
		
		$this->errors[] = $error;
		ValidatorErrorHandler::addError( $error );
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
	 * Determines the names and values of all parameters. Also takes care of default parameters. 
	 * After that the resulting parameter list is passed to Validator::setParameters
	 * 
	 * @param array $rawParams
	 * @param array $parameterInfo
	 * @param array $defaultParams
	 * @param boolean $toLower Indicates if the parameter values should be put to lower case. Defaults to true.
	 */
	public function parseAndSetParams( array $rawParams, array $parameterInfo, array $defaultParams = array(), $toLower = true ) {
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
						
						$this->lowerCaseIfNeeded( $parts[0], $defaultParam, $parameterInfo, $toLower );
						
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
					
					$this->lowerCaseIfNeeded( $parts[1], $paramName, $parameterInfo, $toLower );
					
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
		
		$this->parameterInfo = $parameterInfo;

		// Loop through all the user provided parameters, and destinguise between those that are allowed and those that are not.
		foreach ( $parameters as $paramName => $paramData ) {
			$paramName = trim( strtolower( $paramName ) );
			
			// Attempt to get the main parameter name (takes care of aliases).
			$mainName = self::getMainParamName( $paramName );

			// If the parameter is found in the list of allowed ones, add it to the $mParameters array.
			if ( $mainName ) {
				// Check for parameter overriding. In most cases, this has already largely been taken care off, 
				// in the form of later parameters overriding earlier ones. This is not true for different aliases though.
				if ( !array_key_exists( $mainName, $this->mParameters ) || self::$acceptOverriding ) {
					// If the valueis an array, this means it has been procesed in parseAndSetParams already.
					// If it is not, setParameters was called directly with an array of string parameter values.
					if ( is_array( $paramData ) && array_key_exists( 'original-value', $paramData ) ) {
						$paramData['original-name'] = $paramName;
						$this->mParameters[$mainName] = $paramData;							
					}
					else {
						if ( is_string( $paramData ) ) {
							$paramData = trim( $paramData );
							$this->lowerCaseIfNeeded( $paramData, $mainName, $this->parameterInfo, $toLower );
						}
						
						$this->mParameters[$mainName] = array(
							'original-value' => $paramData,
							'original-name' => $paramName,
						);
					}
				}
				else {
					$this->registerError(
						wfMsgExt(
							'validator-error-override-argument',
							'parsemag',
							$paramName,
							$this->mParameters[$mainName]['original-value'],
							is_array( $paramData ) ? $paramData['original-value'] : $paramData
						),
						'override'		
					);
				}
			}
			else { // If the parameter is not found in the list of allowed ones, add an item to the $this->mErrors array.
				if ( self::$storeUnknownParameters ) $this->mUnknownParams[] = $paramName;
				$this->registerError(
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
	 * Lowercases the provided $paramValue if needed.
	 * 
	 * @since 0.3.6
	 * 
	 * @param $paramValue String
	 * @param $paramName String
	 * @param $parameterInfo Array
	 * @param $globalDefault Boolean
	 */
	protected function lowerCaseIfNeeded( &$paramValue, $paramName, array $parameterInfo, $globalDefault ) {
		$lowerCase = array_key_exists( $paramName, $parameterInfo ) ? $parameterInfo[$paramName]->lowerCaseValue : $globalDefault;
		if ( $lowerCase ) $paramValue = strtolower( $paramValue );
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

		if ( array_key_exists( $paramName, $this->parameterInfo ) ) {
			$result = $paramName;
		}
		else {
			foreach ( $this->parameterInfo as $name => $parameter ) {
				if ( $parameter->hasAlias( $paramName ) ) {
					$result = $name;
					break;
				}
			}
		}

		return $result;
	}	
	
	/**
	 * First determines the order of parameter handling based on the dependency definitons,
	 * and then goes through the parameters one by one, first validating and then formatting,
	 * storing any encountered errors along the way.
	 * 
	 * The 'value' element is set here, either by the cleaned 'original-value' or default.
	 */
	public function validateAndFormatParameters() {
		$dependencyList = array();
		
		foreach ( $this->parameterInfo as $paramName => $parameter ) {
			$dependencyList[$paramName] = $parameter->dependencies;
		}
		
		$sorter = new TopologicalSort( $dependencyList, true );
		$orderedParameters = $sorter->doSort();

		foreach ( $orderedParameters as $paramName ) {
			$parameter = $this->parameterInfo[$paramName];
			
			// If the user provided a value for this parameter, validate and handle it.
			if ( array_key_exists( $paramName, $this->mParameters ) ) {

				$this->cleanParameter( $paramName );

				if ( $this->validateParameter( $paramName ) ) {
					// If the validation succeeded, add the parameter to the list of valid ones.
					$this->mValidParams[] = $paramName;
					$this->setOutputTypes( $paramName );
				}
				else {
					// If the validation failed, add the parameter to the list of invalid ones.
					$this->mInvalidParams[] = $paramName;
				}
			}
			else {
				// If the parameter is required, add a new error of type 'missing'.
				// TODO: break when has dependencies
				if ( $parameter->isRequired() ) {
					$this->registerError(
						wfMsgExt(
							'validator_error_required_missing',
							'parsemag',
							$paramName
						),
						'missing'		
					);
				}
				else {
					// Set the default value.
					$this->mParameters[$paramName]['value'] = $parameter->default; 
					$this->mValidParams[] = $paramName; 
					$this->setOutputTypes( $paramName );
				}
			}
		}
	}

	/**
	 * Ensures the parameter info is valid and parses list types.
	 * 
	 * @param string $name
	 */
	private function cleanParameter( $name ) {
		// If the original-value element is set, clean it, and store as value.
		if ( array_key_exists( 'original-value', $this->mParameters[$name] ) ) {
			$value = $this->mParameters[$name]['original-value'];
			
			if ( $this->parameterInfo[$name]->isList() ) {
				// Trimming and splitting of list values.
				$delimiter = $this->parameterInfo[$name]->getListDelimeter();
				$value = preg_replace( '/((\s)*' . $delimiter . '(\s)*)/', $delimiter, $value );
				$value = explode( $delimiter, $value );
			}
			
			$this->mParameters[$name]['value'] = $value;
		}
	}
	
	/**
	 * Valides the provided parameter. 
	 * 
	 * This method itself validates the list criteria, if any. After this the regular criteria
	 * are validated by calling the doItemValidation method.
	 *
	 * @param string $name
	 *
	 * @return boolean Indicates whether there the parameter value(s) is/are valid.
	 */
	protected function validateParameter( $name ) {
		$hasNoErrors = $this->doListValidation( $name );
		
		if ( $hasNoErrors || self::$accumulateParameterErrors ) {
			$hasNoErrors = $hasNoErrors && $this->doItemValidation( $name );
		}
		
		return $hasNoErrors;
	}
	
	/**
	 * Validates the list criteria for a parameter, if there are any.
	 * 
	 * @param string $name
	 */
	protected function doListValidation( $name ) {
		$hasNoErrors = true;

		/* TODO
		foreach ( $this->parameterInfo[$name]->getListCriteria() as $criteriaName => $criteriaArgs ) {
			// Get the validation function. If there is no matching function, throw an exception.
			if ( array_key_exists( $criteriaName, self::$mListValidationFunctions ) ) {
				$validationFunction = self::$mListValidationFunctions[$criteriaName];
				$isValid = $this->doCriteriaValidation( $validationFunction, $this->mParameters['value'], $name, $criteriaArgs );
				
				// Add a new error when the validation failed, and break the loop if errors for one parameter should not be accumulated.
				if ( ! $isValid ) {
					$hasNoErrors = false;
					
					$this->registerError(
						$this->getCriteriaErrorMessage(
							$criteriaName,
							$this->mParameters[$name]['original-name'],
							$this->mParameters[$name]['original-value'],
							$criteriaArgs,
							true
						),
						$criteriaName		
					);				
					
					if ( !self::$accumulateParameterErrors ) {
						break;
					}
				}
			}
			else {
				$hasNoErrors = false;
				throw new Exception( 'There is no validation function for list criteria type ' . $criteriaName );
			}
		}
		*/
		
		return $hasNoErrors;
	}
	
	/**
	 * Valides the provided parameter by matching the value against the item criteria for the name.
	 * 
	 * @param string $name
	 * 
	 * @return boolean Indicates whether there the parameter value(s) is/are valid.
	 */
	protected function doItemValidation( $name ) {
		$hasNoErrors = true;
		
		$value = &$this->mParameters[$name]['value'];
		
		/* TODO
		// Go through all item criteria.
		foreach ( $this->parameterInfo[$name]->getCriteria() as $criteriaName => $criteriaArgs ) {
			// Get the validation function. If there is no matching function, throw an exception.
			if ( array_key_exists( $criteriaName, self::$mValidationFunctions ) ) {
				$validationFunction = self::$mValidationFunctions[$criteriaName];
				
				if ( is_array( $value ) ) {
					// Handling of list parameters
					$invalidItems = array();
					$validItems = array();
					
					// Loop through all the items in the parameter value, and validate them.
					foreach ( $value as $item ) {
						$isValid = $this->doCriteriaValidation( $validationFunction, $item, $name, $criteriaArgs );
						if ( $isValid ) {
							// If per item validation is on, store the valid items, so only these can be returned by Validator.
							if ( self::$perItemValidation ) $validItems[] = $item;
						}
						else {
							// If per item validation is on, store the invalid items, so a fitting error message can be created.
							if ( self::$perItemValidation ) {
								$invalidItems[] = $item;
							}
							else {
								// If per item validation is not on, an error to one item means the complete value is invalid.
								// Therefore it's not required to validate the remaining items.
								break;
							}
						}
					}
					
					if ( self::$perItemValidation ) {
						// If per item validation is on, the parameter value is valid as long as there is at least one valid item.
						$isValid = count( $validItems ) > 0;
						
						// If the value is valid, but there are invalid items, add an error with a list of these items.
						if ( $isValid && count( $invalidItems ) > 0 ) {
							$value = $validItems;
							
							$this->registerError(
								$this->getCriteriaErrorMessage(
									$criteriaName,
									$this->mParameters[$name]['original-name'],
									$this->mParameters[$name]['original-value'],
									$criteriaArgs,
									true,
									$invalidItems
								),
								$criteriaName		
							);							
						}
					}
				}
				else {
					// Determine if the value is valid for single valued parameters.
					$isValid = $this->doCriteriaValidation( $validationFunction, $value, $name, $criteriaArgs );
				}

				// Add a new error when the validation failed, and break the loop if errors for one parameter should not be accumulated.
				if ( !$isValid ) {
					$this->registerError(
						$this->getCriteriaErrorMessage(
							$criteriaName,
							$this->mParameters[$name]['original-name'],
							$this->mParameters[$name]['original-value'],
							$criteriaArgs,
							is_array( $value )
						),
						$criteriaName		
					);						
					
					$hasNoErrors = false;
					if ( !self::$accumulateParameterErrors ) break;
				}
			}
			else {
				$hasNoErrors = false;
				throw new Exception( 'There is no validation function for criteria type ' . $criteriaName );
			}
		}
		*/
		
		return $hasNoErrors;
	}
	
	/**
	 * Calls the validation function for the provided list or single value and returns it's result.
	 * The call is made with these parameters:
	 * - value: The value that is the complete list, or a single item.
	 * - parameter name: For lookups in the param info array.
	 * - parameter array: All data about the parameters gathered so far (this includes dependencies!).
	 * - output type info: Type info as provided by the parameter definition. This can be zero or more parameters.
	 * 
	 * @param $validationFunction
	 * @param mixed $value
	 * @param string $name
	 * @param array $criteriaArgs
	 * 
	 * @return boolean
	 */
	private function doCriteriaValidation( $validationFunction, $value, $name, array $criteriaArgs ) {
		// Call the validation function and store the result.
		$parameters = array( &$value, $name, $this->mParameters );
		$parameters = array_merge( $parameters, $criteriaArgs );		
		return call_user_func_array( $validationFunction, $parameters );
	}
	
	/**
	 * Changes the invalid parameters to their default values, and changes their state to valid.
	 */
	public function correctInvalidParams() {
		while ( $paramName = array_shift( $this->mInvalidParams ) ) {
			$this->mParameters[$paramName]['value']  =  $this->parameterInfo[$paramName]->default;
			$this->setOutputTypes( $paramName );
			$this->mValidParams[] = $paramName;
		}
	}
	
	/**
	 * Ensures the output type values are arrays, and then calls setOutputType.
	 * 
	 * @param string $name
	 */
	protected function setOutputTypes( $name ) {
		foreach ( $this->parameterInfo[$name]->outputTypes as $outputType ) {
			$this->setOutputType( $name, $outputType );
		}
	}
	
	/**
	 * Calls the formatting function for the provided output format with these parameters:
	 * - parameter value: ByRef for easy manipulation.
	 * - parameter name: For lookups in the param info array.
	 * - parameter array: All data about the parameters gathered so far (this includes dependencies!).
	 * - output type info: Type info as provided by the parameter definition. This can be zero or more parameters.
	 * 
	 * @param string $name
	 * @param array $typeInfo
	 */
	protected function setOutputType( $name, array $typeInfo ) {
		// The output type is the first value in the type info array.
		// The remaining ones will be any extra arguments.
		$outputType = strtolower( array_shift( $typeInfo ) );
		
		if ( !array_key_exists( 'formatted-value', $this->mParameters[$name] ) ) {
			$this->mParameters[$name]['formatted-value'] = $this->mParameters[$name]['value'];
		}
		
		if ( array_key_exists( $outputType, self::$mOutputFormats ) ) {
			$parameters = array( &$this->mParameters[$name]['formatted-value'], $name, $this->mParameters );
			$parameters = array_merge( $parameters, $typeInfo );
			call_user_func_array( self::$mOutputFormats[$outputType], $parameters );
		}
		else {
			throw new Exception( 'There is no formatting function for output format ' . $outputType );
		}
	}

	/**
	 * Returns the valid parameters.
	 *
	 * @param boolean $includeMetaData
	 *
	 * @return array
	 */
	public function getValidParams( $includeMetaData ) {
		if ( $includeMetaData ) {
			return $this->mValidParams;
		}
		else {
			$validParams = array();
			
			foreach( $this->mValidParams as $name ) {
				$key = array_key_exists( 'formatted-value', $this->mParameters[$name] ) ? 'formatted-value' : 'value';
				$validParams[$name] =  $this->mParameters[$name][$key];
			}
			
			return $validParams;			
		}
	}

	/**
	 * Returns the unknown parameters.
	 *
	 * @return array
	 */
	public static function getUnknownParams() {
		$unknownParams = array();
		
		foreach( $this->mUnknownParams as $name ) {
			$unknownParams[$name] = $this->mParameters[$name];
		}		
		
		return $unknownParams;
	}

	/**
	 * Returns the errors.
	 *
	 * @return array of ValidatorError
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
			if ( $error->severity >= ValidatorError::SEVERITY_CRITICAL ) {
				$has = true;
				break;
			}
		}
		
		return $has;
	}	
	
	/**
	 * Returns an error message for a criteria validation that failed.
	 * 
	 * TODO: integrate this further with the hook mechanisms
	 * TODO: proper escaping
	 * 
	 * @since 0.4
	 * 
	 * @param string $criteria The name of the criteria
	 * @param string $paramName The name of the parameter, as provided by the user
	 * @param string $paramValue The value of the parameter, as provided by the user
	 * @param array $args The criteria arguments
	 * @param boolean $isList Indicates if the parameter is a list type or not
	 * @param array $invalidItems Can contain a list of invalid items for list parameters
	 * 
	 * @return string
	 */
	protected function getCriteriaErrorMessage( $criteria, $paramName, $paramValue, array $args = array(), $isList = false, array $invalidItems = array() ) {
		global $wgLang, $egValidatorErrorLevel;
		
		if ( $isList ) {
			switch ( $criteria ) {
				case 'not_empty' :
					$message = wfMsgExt( 'validator_list_error_empty_argument', array( 'parsemag' ), $paramName );
					break;
				case 'in_range' :
					$message = wfMsgExt( 'validator_list_error_invalid_range', array( 'parsemag' ),$paramName, '<b>' . $args[0] . '</b>', '<b>' . $args[1] . '</b>' );
					break;
				case 'is_numeric' :
					$message = wfMsgExt( 'validator_list_error_must_be_number', array( 'parsemag' ), $paramName );
					break;
				case 'is_integer' :
					$message = wfMsgExt( 'validator_list_error_must_be_integer', array( 'parsemag' ), $paramName );
					break;
				case 'in_array' :
					$itemsText = $wgLang->listToText( $args );
					$message = wfMsgExt( 'validator_error_accepts_only', array( 'parsemag' ), $paramName, $itemsText, count( $args ), $paramValue );
					break;
				case 'invalid' : default :
					$message = wfMsgExt( 'validator_list_error_invalid_argument', array( 'parsemag' ), $paramName );
					break;				
			}
			
			if ( count( $invalidItems ) > 0 ) {
				foreach ( $invalidItems as &$item ) {
					$item = Sanitizer::escapeId( $item );
				}
				
				$message .= ' ';
				$message .= wfMsgExt(
					'validator_list_omitted',
					array( 'parsemag' ),
					$wgLang->listToText( $invalidItems ),
					count( $invalidItems )
				);
			}			
		}
		else {
			switch ( $criteria ) {
				case 'not_empty' :
					$message = wfMsgExt( 'validator_error_empty_argument', array( 'parsemag' ), $paramName );
					break;
				case 'in_range' :
					$message = wfMsgExt( 'validator_error_invalid_range', array( 'parsemag' ), $paramName, '<b>' . $args[0] . '</b>', '<b>' . $args[1] . '</b>' );
					break;
				case 'is_numeric' :
					$message = wfMsgExt( 'validator_error_must_be_number', array( 'parsemag' ), $paramName );
					break;
				case 'is_integer' :
					$message = wfMsgExt( 'validator_error_must_be_integer', array( 'parsemag' ), $paramName );
					break;
				case 'in_array' :
					$itemsText = $wgLang->listToText( $args );
					$message = wfMsgExt( 'validator_error_accepts_only', array( 'parsemag' ), $paramName, $itemsText, count( $args ), $paramValue );
					break;
				case 'invalid' : default :
					$message = wfMsgExt( 'validator_error_invalid_argument', array( 'parsemag' ), '<b>' . htmlspecialchars( $paramValue ) . '</b>', $paramName );
					break;				
			}
		}
		
		return $message;	
	}
	
}