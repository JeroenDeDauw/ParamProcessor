<?php

/**
 * File holding the Validator class.
 *
 * @file Validator.class.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

/**
 * Class for parameter validation.
 *
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */
final class Validator {

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
	 * @var array Holder for the validation functions.
	 */
	private static $mValidationFunctions = array(
			'in_array' => array( 'ValidatorFunctions', 'in_array' ),
			'in_range' => array( 'ValidatorFunctions', 'in_range' ),
			'is_numeric' => 'is_numeric',
			'is_float' => 'is_float',
			'is_integer' => array( 'ValidatorFunctions', 'is_integer' ),
			'not_empty' => array( 'ValidatorFunctions', 'not_empty' ),
			'has_length' => array( 'ValidatorFunctions', 'has_length' ),
			'regex' => array( 'ValidatorFunctions', 'regex' ),
			);
	
	/**
	 * @var array Holder for the list validation functions.
	 */
	private static $mListValidationFunctions = array(
			'item_count' => array( 'ValidatorFunctions', 'has_item_count' ),
			'unique_items' => array( 'ValidatorFunctions', 'has_unique_items' ),
			);

	/**
	 * @var array Holder for the formatting functions.
	 */
	private static $mOutputFormats = array(
			'array' => array( 'ValidatorFormats', 'format_array' ),
			'list' => array( 'ValidatorFormats', 'format_list' ),
			'boolean' => array( 'ValidatorFormats', 'format_boolean' ),
			'boolstr' => array( 'ValidatorFormats', 'format_boolean_string' ),
			'string' => array( 'ValidatorFormats', 'format_string' ),
			'unique_items' => array( 'ValidatorFormats', 'format_unique_items' ),
			'filtered_array' => array( 'ValidatorFormats', 'format_filtered_array' ),
			);

	private $mParameterInfo;
	
	private $mRawParameters = array();

	private $mParameters = array();
	private $mValidParams = array();
	private $mInvalidParams = array();
	private $mUnknownParams = array();

	private $mErrors = array();

	/**
	 * Sets the parameter criteria, used to valiate the parameters.
	 *
	 * @param array $parameterInfo
	 * @param array $defaultParams
	 */
	public function setParameterInfo( array $parameterInfo ) {
		$this->mParameterInfo = $parameterInfo;
	}

	/**
	 * Sets the raw parameters that will be validated when validateParameters is called.
	 *
	 * @param array $parameters
	 */
	public function setParameters( array $parameters ) {
		$this->mRawParameters = $parameters;
	}

	/**
	 * Determine all parameter names and value, and take care of default (nameless)
	 * parameters, by turning them into named ones.
	 * 
	 * @param array $rawParams
	 * @param array $defaultParams
	 */
	public function parseAndSetParams( array $rawParams, array $defaultParams = array() ) {
		$parameters = array();

		foreach( $rawParams as $arg ) {
			// Only take into account strings. If the value is not a string,
			// it is not a raw parameter, and can not be parsed correctly in all cases.
			if ( is_string( $arg ) ) {
				$parts = explode( '=', $arg );
				if ( count( $parts ) == 1 ) {
					if ( count( $defaultParams ) > 0 ) {
						$defaultParam = array_shift( $defaultParams ); 
						$parameters[$defaultParam] = trim( $parts[0] );	
					}
				} else {
					$name = strtolower( trim( array_shift( $parts ) ) );
					$parameters[$name] = trim( implode( $parts ) );
				}				
			}
		}		
		
		$this->setParameters( $parameters );
	}
	
	/**
	 * Valides the raw parameters, and allocates them as valid, invalid or unknown.
	 * Errors are collected, and can be retrieved via getErrors.
	 *
	 * @return boolean Indicates whether there where NO errors.
	 */
	public function validateParameters() {
		// Loop through all the user provided parameters, and destinguise between those that are allowed and those that are not.
		foreach ( $this->mRawParameters as $paramName => $paramValue ) {
			// Attempt to get the main parameter name (takes care of aliases).
			$mainName = self::getMainParamName( $paramName );
			// If the parameter is found in the list of allowed ones, add it to the $mParameters array.
			if ( $mainName ) {
				// Check for parameter overriding. In most cases, this has already largely been taken care off, 
				// in the form of later parameters overriding earlier ones. This is not true for different aliases though.
				if ( !array_key_exists( $mainName, $this->mParameters ) || self::$acceptOverriding ) {
					$this->mParameters[$mainName] = $paramValue;
				}
				else {
					$this->errors[] = array( 'type' => 'override', 'name' => $mainName );
				}
			}
			else { // If the parameter is not found in the list of allowed ones, add an item to the $this->mErrors array.
				if ( self::$storeUnknownParameters ) $this->mUnknownParams[$paramName] = $paramValue;
				$this->mErrors[] = array( 'type' => 'unknown', 'name' => $paramName );
			}
		}

		// Loop through the list of allowed parameters.
		foreach ( $this->mParameterInfo as $paramName => $paramInfo ) {
			// If the user provided a value for this parameter, validate and handle it.
			if ( array_key_exists( $paramName, $this->mParameters ) ) {

				$paramValue = $this->mParameters[$paramName];
				$this->cleanParameter( $paramName, $paramValue );

				if ( $this->validateParameter( $paramName, $paramValue ) ) {
					// If the validation succeeded, add the parameter to the list of valid ones.
					$this->mValidParams[$paramName] = $paramValue;
					$this->setOutputTypes( $this->mValidParams[$paramName], $paramInfo );
				}
				else {
					// If the validation failed, add the parameter to the list of invalid ones.
					$this->mInvalidParams[$paramName] = $paramValue;
				}
			}
			else {
				// If the parameter is required, add a new error of type 'missing'.
				if ( array_key_exists( 'required', $paramInfo ) && $paramInfo['required'] ) {
					$this->errors[] = array( 'type' => 'missing', 'name' => $paramName );
				}
				else {
					// Set the default value (or default 'default value' if none is provided), and ensure the type is correct.
					$this->mValidParams[$paramName] = array_key_exists( 'default', $paramInfo ) ? $paramInfo['default'] : '';
					$this->setOutputTypes( $this->mValidParams[$paramName], $paramInfo );
				}
			}
		}

		return count( $this->mErrors ) == 0;
	}

	/**
	 * Returns the main parameter name for a given parameter or alias, or false
	 * when it is not recognized as main parameter or alias.
	 *
	 * @param string $paramName
	 *
	 * @return string
	 */
	private function getMainParamName( $paramName ) {
		$result = false;

		if ( array_key_exists( $paramName, $this->mParameterInfo ) ) {
			$result = $paramName;
		}
		else {
			foreach ( $this->mParameterInfo as $name => $data ) {
				if ( array_key_exists( 'aliases', $data ) && in_array( $paramName, $data['aliases'] ) ) {
					$result = $name;
					break;
				}
			}
		}

		return $result;
	}

	/**
	 * Ensures the parameter info is valid, trims the value, and splits lists.
	 * 
	 * @param string $name
	 * @param $value
	 */
	private function cleanParameter( $name, &$value ) {
		// Ensure there is a criteria array.
		if ( ! array_key_exists( 'criteria', $this->mParameterInfo[$name] ) ) {
			$this->mParameterInfo[$name]['criteria'] = array();
		}
		
		// Ensure the type is set in array form.
		if ( ! array_key_exists( 'type', $this->mParameterInfo[$name] ) ) {
			$this->mParameterInfo[$name]['type'] = array( 'string' );
		}
		elseif ( ! is_array( $this->mParameterInfo[$name]['type'] ) ) {
			$this->mParameterInfo[$name]['type'] = array( $this->mParameterInfo[$name]['type'] );
		}
		
		if ( array_key_exists( 'type', $this->mParameterInfo[$name] ) ) {
			// Add type specific criteria.
			switch( strtolower( $this->mParameterInfo[$name]['type'][0] ) ) {
				case 'integer':
					$this->addTypeCriteria( $name, 'is_integer' );
					break;
				case 'float':
					$this->addTypeCriteria( $name, 'is_float' );
					break;					
				case 'number': // Note: This accepts non-decimal notations! 
					$this->addTypeCriteria( $name, 'is_numeric' );
					break;					
				case 'boolean':
					// TODO: work with list of true and false values. 
					// TODO: i18n
					$this->addTypeCriteria( $name, 'in_array', array( 'yes', 'no', 'on', 'off' ) );
					break;
				case 'char':
					$this->addTypeCriteria( $name, 'has_length', array( 1, 1 ) );
					break;
			}
		}
		
		if ( count( $this->mParameterInfo[$name]['type'] ) > 1 && $this->mParameterInfo[$name]['type'][1] == 'list' ) {
			// Trimming and splitting of list values.
			$delimiter = count( $this->mParameterInfo[$name]['type'] ) > 2 ? $this->mParameterInfo[$name]['type'][2] : ',';
			$value = preg_replace( '/((\s)*' . $delimiter . '(\s)*)/', $delimiter, $value );
			$value = explode( $delimiter, $value );
		}
		elseif ( count( $this->mParameterInfo[$name]['type'] ) > 1 && $this->mParameterInfo[$name]['type'][1] == 'array' && is_array( $value ) ) {
			// Trimming of array values.
			for ( $i = count( $value ); $i > 0; $i-- ) $value[$i] = trim ( $value[$i] );
		}
		else {
			// Trimming of non-list values.
			$value = trim ( $value );
		}
	}
	
	private function addTypeCriteria( $paramName, $criteriaName, $criteriaArgs = array() ) {
		$this->mParameterInfo[$paramName]['criteria'] = array_merge( array( $criteriaName => $criteriaArgs ), $this->mParameterInfo[$paramName]['criteria'] );
	}
	
	/**
	 * Valides the provided parameter by matching the value against the list and item criteria for the name.
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return boolean Indicates whether there the parameter value(s) is/are valid.
	 */
	private function validateParameter( $name, &$value ) {
		$hasNoErrors = true;
		$checkItemCriteria = true;
		
		if ( array_key_exists( 'list-criteria', $this->mParameterInfo[$name] ) ) {
			foreach ( $this->mParameterInfo[$name]['list-criteria'] as $criteriaName => $criteriaArgs ) {
				// Get the validation function. If there is no matching function, throw an exception.
				if ( array_key_exists( $criteriaName, self::$mListValidationFunctions ) ) {
					$validationFunction = self::$mListValidationFunctions[$criteriaName];
					$isValid = $this->doCriteriaValidation( $validationFunction, $value, $criteriaArgs );
					
					// Add a new error when the validation failed, and break the loop if errors for one parameter should not be accumulated.
					if ( ! $isValid ) {
						$hasNoErrors = false;
						
						$this->errors[] = array( 'type' => $criteriaName, 'args' => $criteriaArgs, 'name' => $name, 'list' => true, 'value' => $this->rawParameters[$name] );
						
						if ( ! self::$accumulateParameterErrors ) {
							$checkItemCriteria = false;
							break;
						}
					}
				}
				else {
					$hasNoErrors = false;
					throw new Exception( 'There is no validation function for list criteria type ' . $criteriaName );
				}
			}
		}

		if ( $checkItemCriteria ) $hasNoErrors = $hasNoErrors && $this->doItemValidation( $name, $value );

		return $hasNoErrors;
	}
	
	/**
	 * Valides the provided parameter by matching the value against the item criteria for the name.
	 * 
	 * @param $name
	 * @param $value
	 * 
	 * @return boolean Indicates whether there the parameter value(s) is/are valid.
	 */
	private function doItemValidation( $name, &$value ) {
		$hasNoErrors = true;
		
		// Go through all item criteria.
		foreach ( $this->mParameterInfo[$name]['criteria'] as $criteriaName => $criteriaArgs ) {
			// Get the validation function. If there is no matching function, throw an exception.
			if ( array_key_exists( $criteriaName, self::$mValidationFunctions ) ) {
				$validationFunction = self::$mValidationFunctions[$criteriaName];
				
				if ( is_array( $value ) ) {
					// Handling of list parameters
					$invalidItems = array();
					$validItems = array();
					
					// Loop through all the items in the parameter value, and validate them.
					foreach ( $value as $item ) {
						$isValid = $this->doCriteriaValidation( $validationFunction, $item, $criteriaArgs );
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
							$this->errors[] = array( 'type' => $criteriaName, 'args' => $criteriaArgs, 'name' => $name, 'list' => true, 'invalid-items' => $invalidItems );
						}
					}
				}
				else {
					// Determine if the value is valid for single valued parameters.
					$isValid = $this->doCriteriaValidation( $validationFunction, $value, $criteriaArgs );
				}
				
				// Add a new error when the validation failed, and break the loop if errors for one parameter should not be accumulated.
				if ( ! $isValid ) {
					$isList = is_array( $value );
					if ( $isList ) $value = $this->mRawParameters[$name];
					$this->mErrors[] = array( 'type' => $criteriaName, 'args' => $criteriaArgs, 'name' => $name, 'list' => $isList, 'value' => $value );
					$hasNoErrors = false;
					if ( ! self::$accumulateParameterErrors ) break;
				}
			}
			else {
				$hasNoErrors = false;
				throw new Exception( 'There is no validation function for criteria type ' . $criteriaName );
			}
		}

		return $hasNoErrors;
	}
	
	/**
	 * Validates the value of an item, and returns the validation result.
	 * 
	 * @param $validationFunction
	 * @param $value
	 * @param $criteriaArgs
	 * 
	 * @return unknown_type
	 */
	private function doCriteriaValidation( $validationFunction, $value, $criteriaArgs ) {
		// Call the validation function and store the result. 
		return call_user_func_array( $validationFunction, array_merge( array( $value ), $criteriaArgs ) );
	}
	
	/**
	 * Changes the invalid parameters to their default values, and changes their state to valid.
	 */
	public function correctInvalidParams() {
		foreach ( $this->mInvalidParams as $paramName => $paramValue ) {
			unset( $this->mInvalidParams[$paramName] );
			$this->mValidParams[$paramName] = array_key_exists( 'default', $this->mParameterInfo[$paramName] ) ? $this->mParameterInfo[$paramName]['default'] : '';
			$this->setOutputTypes( $this->mValidParams[$paramName], $this->mParameterInfo[$paramName] );
		}
	}
	
	/**
	 * Ensures the output type values are arrays, and then calls setOutputType.
	 * 
	 * @param $value
	 * @param array $info
	 */
	private function setOutputTypes( &$value, array $info ) {
		if ( array_key_exists( 'output-types', $info ) ) {
			for ( $i = 0, $c = count( $info['output-types'] ); $i < $c; $i++ ) {
				if ( ! is_array( $info['output-types'][$i] ) ) $info['output-types'][$i] = array( $info['output-types'][$i] );
				$this->setOutputType( $value, $info['output-types'][$i] );
			}
		}
		elseif ( array_key_exists( 'output-type', $info ) ) {
			if ( ! is_array( $info['output-type'] ) ) $info['output-type'] = array( $info['output-type'] );
			$this->setOutputType( $value, $info['output-type'] );
		}
		
	}
	
	/**
	 * Calls the formatting function for the provided output format with the provided value.
	 * 
	 * @param $value
	 * @param array $typeInfo
	 */
	private function setOutputType( &$value, array $typeInfo ) {
		// The output type is the first value in the type info array.
		// The remaining ones will be any extra arguments.
		$outputType = strtolower( array_shift( $typeInfo ) );
		
		if ( array_key_exists( $outputType, self::$mOutputFormats ) ) {
			// Call the formatting function with as first parameter the value, followed by the extra arguments.
			call_user_func_array( self::$mOutputFormats[$outputType], array_merge( array( &$value ), $typeInfo ) );
		}
		else {
			throw new Exception( 'There is no formatting function for output format ' . $outputType );
		}
	}

	/**
	 * Returns the valid parameters.
	 *
	 * @return array
	 */
	public function getValidParams() {
		return $this->mValidParams;
	}

	/**
	 * Returns the unknown parameters.
	 *
	 * @return array
	 */
	public static function getUnknownParams() {
		return $this->mUnknownParams;
	}

	/**
	 * Returns the errors.
	 *
	 * @return array
	 */
	public function getErrors() {
		return $this->mErrors;
	}

	/**
	 * Adds a new criteria type and the validation function that should validate values of this type.
	 * You can use this function to override existing criteria type handlers.
	 *
	 * @param string $criteriaName The name of the cirteria.
	 * @param array $functionName The functions location. If it's a global function, only the name,
	 * if it's in a class, first the class name, then the method name.
	 */
	public static function addValidationFunction( $criteriaName, array $functionName ) {
		self::$mValidationFunctions[$criteriaName] = $functionName;
	}
	
	/**
	 * Adds a new list criteria type and the validation function that should validate values of this type.
	 * You can use this function to override existing criteria type handlers.
	 *
	 * @param string $criteriaName The name of the list cirteria.
	 * @param array $functionName The functions location. If it's a global function, only the name,
	 * if it's in a class, first the class name, then the method name.
	 */
	public static function addListValidationFunction( $criteriaName, array $functionName ) {
		self::$mListValidationFunctions[$criteriaName] = $functionName;
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
		self::$mOutputFormats[$formatName] = $functionName;
	}
}