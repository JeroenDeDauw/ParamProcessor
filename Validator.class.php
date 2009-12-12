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
	 * @var array Holder for the validation functions.
	 */
	private static $validationFunctions = array(
			'in_array' => 'in_array',
			'in_range' => array( 'ValidatorFunctions', 'in_range' ),
			'is_numeric' => 'is_numeric',
			'is_integer' => array( 'ValidatorFunctions', 'is_integer' ),
			'not_empty' => array( 'ValidatorFunctions', 'not_empty' ),
			'all_in_array' => array( 'ValidatorFunctions', 'all_in_array' ),
			'any_in_array' => array( 'ValidatorFunctions', 'any_in_array' ),
			);

	private $parameterInfo;
	private $rawParameters = array();

	private $valid = array();
	private $invalid = array();
	private $unknown = array();

	private $errors = array();

	/**
	 * Sets the parameter criteria, used to valiate the parameters.
	 *
	 * @param array $parameterInfo
	 */
	public function setParameterInfo( array $parameterInfo ) {
		$this->parameterInfo = $parameterInfo;
	}

	/**
	 * Sets the raw parameters that will be validated when validateParameters is called.
	 *
	 * @param array $parameters
	 */
	public function setParameters( array $parameters ) {
		$this->rawParameters = $parameters;
	}

	/**
	 * Valides the raw parameters, and allocates them as valid, invalid or unknown.
	 * Errors are collected, and can be retrieved via getErrors.
	 *
	 * @return boolean Indicates whether there where no errors.
	 */
	public function validateParameters() {

		$parameters = array();

		// Loop through all the user provided parameters, and destinguise between those that are allowed and those that are not.
		foreach ( $this->rawParameters as $paramName => $paramValue ) {
			// Attempt to get the main parameter name (takes care of aliases).
			$mainName = self::getMainParamName( $paramName, $this->parameterInfo );
			// If the parameter is found in the list of allowed ones, add it to the $parameters array.
			if ( $mainName ) {
				$parameters[$mainName] = $paramValue;
			}
			else { // If the parameter is not found in the list of allowed ones, add an item to the $this->errors array.
				if ( self::$storeUnknownParameters ) $this->unknown[$paramName] = $paramValue;
				$this->errors[] = array( 'error' => array( 'unknown' ), 'name' => $paramName );
			}
		}

		// Loop through the list of allowed parameters.
		foreach ( $this->parameterInfo as $paramName => $paramInfo ) {
			// If the user provided a value for this parameter, validate and handle it.
			if ( array_key_exists( $paramName, $this->rawParameters ) ) {

				$paramValue = $this->rawParameters[$paramName];
				$this->cleanParameter( $paramName, $paramValue );
				$validationErrors = $this->validateParameter( $paramName, $paramValue );

				if ( count( $validationErrors ) == 0 ) {
					// If the validation succeeded, add the parameter to the list of valid ones.
					$this->valid[$paramName] = $paramValue;
				}
				else {
					// If the validation failed, add the parameter to the list of invalid ones and add the errors to the error list.
					$this->invalid[$paramName] = $paramValue;
					foreach ( $validationErrors as $error ) {
						$this->errors[] = array( 'error' => $error, 'name' => $paramName );
					}
				}
			}
			else {
				// If the parameter is required, add a new error of type 'missing'.
				if ( array_key_exists( 'required', $paramInfo ) && $paramInfo['required'] ) {
					$this->errors[] = array( 'error' => array( 'missing' ), 'name' => $paramName );
				}
				else {
					// Set the default value (or default 'default value' if none is provided), and ensure the type is correct.
					$this->valid[$paramName] = array_key_exists( 'default', $paramInfo ) ? $paramInfo['default'] : '';	
					$this->setFinalType($this->valid[$paramName], $paramInfo);	
				}
			}
		}

		return count( $this->errors ) == 0;
	}

	/**
	 * Returns the main parameter name for a given parameter or alias, or false
	 * when it is not recognized as main parameter or alias.
	 *
	 * @param string $paramName
	 * @param array $allowedParms
	 *
	 * @return string
	 */
	private function getMainParamName( $paramName, array $allowedParms ) {
		$result = false;

		if ( array_key_exists( $paramName, $allowedParms ) ) {
			$result = $paramName;
		}
		else {
			foreach ( $allowedParms as $name => $data ) {
				if ( array_key_exists( 'aliases', $data ) ) {
					if ( in_array( $paramName, $data['aliases'] ) ) {
						$result = $name;
						break;
					}
				}
			}
		}

		return $result;
	}

	/**
	 * 
	 * @param $name
	 * @param $value
	 * 
	 * @return unknown_type
	 */
	private function cleanParameter( $name, &$value ) {
		if (array_key_exists('default', $this->parameterInfo[$name])) {
			$this->setFinalType($this->parameterInfo[$name]['default'], $this->parameterInfo[$name]);
		}		
		
		// Ensure there is a criteria array.
		if (! array_key_exists('criteria', $this->parameterInfo[$name] )) {
			$this->parameterInfo[$name]['criteria'] = array();
		}				
		
		if ( array_key_exists( 'type', $this->parameterInfo[$name] ) ) {
					// Add type specific criteria.
			switch(strtolower($this->parameterInfo[$name]['type'])) {
				case 'integer':
					$this->parameterInfo[$name]['criteria']['is_integer'] = array();
					break;
				case 'list' : case 'list-string' :
					if (! array_key_exists('delimiter', $this->parameterInfo[$name])) $this->parameterInfo[$name]['delimiter'] = ',';
			}			
			
			// Remove redundant spaces.
			switch(strtolower($this->parameterInfo[$name]['type'])) {
				case 'list' : case 'list-string':
					// TODO: make sure the delimiter doesn't mess up the regex when it's a special char.
					$value = preg_replace('/((\s)*' . 
						$this->parameterInfo[$name]['delimiter'] .
						'(\s)*)/', $this->parameterInfo[$name]['delimiter'], $value);
					break;
				default :
					$value = trim ($value);
					break;				
			}
		}		
	}
	
	/**
	 * Valides the provided parameter by matching the value against the criteria for the name.
	 *
	 * @param string $name
	 * @param string $value
	 *
	 * @return array The errors that occured during validation.
	 */
	private function validateParameter( $name, $value ) {
		$errors = array();
		
		// Split list types into arrays.
		switch(strtolower($this->parameterInfo[$name]['type'])) {
			case 'list' : case 'list-string' :
				$value = explode($this->parameterInfo[$name]['delimiter'], $value);
				break;
		}
		
		// Go through all criteria.
		foreach ( $this->parameterInfo[$name]['criteria'] as $criteriaName => $criteriaArgs ) {
			// Get the validation function. If there is no matching function, throw an exception.
			if (array_key_exists($criteriaName, self::$validationFunctions)) {
				$validationFunction = self::$validationFunctions[$criteriaName];
			}
			else {
				throw new Exception( 'There is no validation function for criteria type ' . $criteriaName );
			}
			
			// Build up the array of parameters to be passed to call_user_func_array.
			$arguments = array( $value );
			if ( count( $criteriaArgs ) > 0 ) $arguments[] = $criteriaArgs;
			
			// Call the validation function and store the result. 
			$isValid = call_user_func_array( $validationFunction, $arguments );

			// Add a new error when the validation failed, and break the loop if errors for one parameter should not be accumulated.
			if ( ! $isValid ) {
				$errors[] = array( $criteriaName, $criteriaArgs, $value );
				if ( ! self::$accumulateParameterErrors ) break;
			}
		}

		return $errors;
	}
	
	/**
	 * Ensures the type of the value is correct. 
	 * 
	 * @param $value
	 * @param array $info
	 */
	private function setFinalType(&$value, array $info) {
		if (array_key_exists('type', $info)) {
			switch(strtolower($info['type'])) {
				case 'list-string' :
					if (is_array($value)) {
						$delimiter = array_key_exists('delimiter', $info) ? $info['delimiter'] : ',';
						$value = implode($delimiter, $value);
					}
					break;
			}			
		}
	}

	/**
	 * Changes the invalid parameters to their default values, and changes their state to valid.
	 */
	public function correctInvalidParams() {
		foreach ( $this->invalid as $paramName => $paramValue ) {
			unset( $this->invalid[$paramName] );
			$this->valid[$paramName] = array_key_exists( 'default', $this->parameterInfo[$paramName] ) ? $this->parameterInfo[$paramName]['default'] : '';
			$this->setFinalType($this->valid[$paramName], $this->parameterInfo[$paramName]);
		}
	}

	/**
	 * Returns the valid parameters.
	 *
	 * @return array
	 */
	public function getValidParams() {
		return $this->valid;
	}

	/**
	 * Returns the unknown parameters.
	 *
	 * @return array
	 */
	public static function getUnknownParams() {
		return $this->unknown;
	}

	/**
	 * Returns the errors.
	 *
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
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
		$this->validationFunctions[$criteriaName] = $functionName;
	}
}
