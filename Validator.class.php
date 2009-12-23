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
	private static $validationFunctions = array(
			'in_array' => 'in_array',
			'in_range' => array( 'ValidatorFunctions', 'in_range' ),
			'is_numeric' => 'is_numeric',
			'is_integer' => array( 'ValidatorFunctions', 'is_integer' ),
			'is_boolean' => array( 'ValidatorFunctions', 'is_boolean' ),	
			'not_empty' => array( 'ValidatorFunctions', 'not_empty' ),
			'has_length' => array( 'ValidatorFunctions', 'has_length' ),
			);
	
	/**
	 * @var array Holder for the list validation functions.
	 */
	private static $listValidationFunctions = array(
			'item_count' => array( 'ValidatorFunctions', 'item_count' ),
			'unique_items' => array( 'ValidatorFunctions', 'unique_items' ),
			);

	/**
	 * @var array Holder for the formatting functions.
	 */			
	private static $outputFormats = array(
			'array' => array( 'ValidatorFormats', '' ),
			'list' => array( 'ValidatorFormats', '' ),
			'boolean' => array( 'ValidatorFormats', '' ),
			'string' => array( 'ValidatorFormats', '' ),
			);

	private $parameterInfo;
	private $rawParameters = array();

	private $parameters= array();
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
				// Check for parameter overriding. In most cases, this has already largely been taken care off, 
				// in the form of later parameters overriding earlier ones. This is not true for different aliases though.
				if (! array_key_exists($mainName, $this->parameters) || self::$acceptOverriding ) {
					$this->parameters[$mainName] = $paramValue;
				}
				else {
					$this->errors[] = array( 'type' => 'unknown', 'name' => $mainName );
				}
			}
			else { // If the parameter is not found in the list of allowed ones, add an item to the $this->errors array.
				if ( self::$storeUnknownParameters ) $this->unknown[$paramName] = $paramValue;
				$this->errors[] = array( 'type' => 'unknown', 'name' => $paramName);
			}
		}

		// Loop through the list of allowed parameters.
		foreach ( $this->parameterInfo as $paramName => $paramInfo ) {
			// If the user provided a value for this parameter, validate and handle it.
			if ( array_key_exists( $paramName, $this->parameters ) ) {

				$paramValue = $this->parameters[$paramName];
				$this->cleanParameter( $paramName, $paramValue );

				if ( $this->validateParameter( $paramName, $paramValue ) ) {
					// If the validation succeeded, add the parameter to the list of valid ones.
					$this->valid[$paramName] = $paramValue;
					$this->setOutputType($this->valid[$paramName], $paramInfo);	
				}
				else {
					// If the validation failed, add the parameter to the list of invalid ones.
					$this->invalid[$paramName] = $paramValue;
				}
			}
			else {
				// If the parameter is required, add a new error of type 'missing'.
				if ( array_key_exists( 'required', $paramInfo ) && $paramInfo['required'] ) {
					$this->errors[] = array( 'type' => 'missing', 'name' => $paramName );
				}
				else {
					// Set the default value (or default 'default value' if none is provided), and ensure the type is correct.
					$this->valid[$paramName] = array_key_exists( 'default', $paramInfo ) ? $paramInfo['default'] : '';	
					$this->setOutputType($this->valid[$paramName], $paramInfo);	
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
	 * Ensures the parameter info is valid, trims the value, and splits lists.
	 * 
	 * @param string $name
	 * @param $value
	 */
	private function cleanParameter( $name, &$value ) {		
		// Ensure there is a criteria array.
		if (! array_key_exists('criteria', $this->parameterInfo[$name] )) {
			$this->parameterInfo[$name]['criteria'] = array();
		}			
		
		// Ensure the type is set in array form.
		if (! array_key_exists('type', $this->parameterInfo[$name] )) {
			$this->parameterInfo[$name]['type'] = array('string');
		}
		elseif(! is_array($this->parameterInfo[$name]['type'])) {
			$this->parameterInfo[$name]['type'] = array($this->parameterInfo[$name]['type']);
		}
		
		if ( array_key_exists( 'type', $this->parameterInfo[$name] ) ) {
			// Add type specific criteria.
			switch(strtolower($this->parameterInfo[$name]['type'][0])) {			
				case 'integer':
					$this->parameterInfo[$name]['criteria']['is_integer'] = array();
					break;
				case 'number':
					$this->parameterInfo[$name]['criteria']['is_numeric'] = array();
					break;
				case 'boolean':
					$this->parameterInfo[$name]['criteria']['is_boolean'] = array();
					break;
				case 'char':
					$this->parameterInfo[$name]['criteria']['has_length'] = array(1);
					break;										
			}
		}
		
		if (count($this->parameterInfo[$name]['type']) > 1 && $this->parameterInfo[$name]['type'][1] == 'list') {
			// Trimming and splitting of list values.
			$delimiter = count($this->parameterInfo[$name]['type']) > 2 ? $this->parameterInfo[$name]['type'][2] : ',';
			$value = preg_replace('/((\s)*' . $delimiter . '(\s)*)/', $delimiter, $value);
			$value = explode($delimiter, $value);
			
			// Ensure there is a criteria array.
			if (! array_key_exists('list-criteria', $this->parameterInfo[$name] )) {
				$this->parameterInfo[$name]['list-criteria'] = array();
			}	
		}
		else {
			// Trimming of non-list values.
			$value = trim ($value);
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
	private function validateParameter( $name, &$value ) {
		$hasNoErrors = true;
		
		// Go through all criteria.
		foreach ( $this->parameterInfo[$name]['criteria'] as $criteriaName => $criteriaArgs ) {
			// Get the validation function. If there is no matching function, throw an exception.
			if (array_key_exists($criteriaName, self::$validationFunctions)) {
				$validationFunction = self::$validationFunctions[$criteriaName];
			}
			else {
				throw new Exception( 'There is no validation function for criteria type ' . $criteriaName );
			}
			
			if (is_array($value)) {
				// Handling of list parameters
				$invalidItems = array();
				$validItems = array();
				
				// Loop through all the items in the parameter value, and validate them.
				foreach($value as $item) {
					$isValid = $this->doItemValidation($validationFunction, $item, $criteriaArgs);
					if ($isValid) {
						// If per item validation is on, store the valid items, so only these can be returned by Validator.
						if (self::$perItemValidation) $validItems[] = $item;
					}
					else {
						// If per item validation is on, store the invalid items, so a fitting error message can be created.
						if (self::$perItemValidation) {
							$invalidItems[] = $item;
						}
						else {
							// If per item validation is not on, an error to one item means the complete value is invalid.
							// Therefore it's not required to validate the remaining items.
							break;
						}
					}
				}
				
				if (self::$perItemValidation) {
					// If per item validation is on, the parameter value is valid as long as there is at least one valid item.
					$isValid = count($validItems) > 0;
					
					// If the value is valid, but there are invalid items, add an error with a list of these items.
					if ($isValid && count($invalidItems) > 0) {
						$value = $validItems;
						$this->errors[] = array( 'type' => $criteriaName, 'args' => $criteriaArgs, 'name' => $name, 'list' => true, 'invalid-items' => $invalidItems );
					}
				}
				
			}
			else {
				// Determine if the value is valid for single valued parameters.
				$isValid = $this->doItemValidation($validationFunction, $value, $criteriaArgs);
			}
			
			// Add a new error when the validation failed, and break the loop if errors for one parameter should not be accumulated.
			if ( ! $isValid ) {
				$isList = is_array($value);
				if ($isList) $value = $this->rawParameters[$name];
				$this->errors[] = array( 'type' => $criteriaName, 'args' => $criteriaArgs, 'name' => $name, 'list' => $isList, 'value' => $value );
				$hasNoErrors = false;
				if ( ! self::$accumulateParameterErrors ) break;
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
	private function doItemValidation($validationFunction, $value, $criteriaArgs) {
		// Build up the array of parameters to be passed to call_user_func_array.
		$arguments = array( $value );
		if ( count( $criteriaArgs ) > 0 ) $arguments[] = $criteriaArgs;
		
		// Call the validation function and store the result. 
		return call_user_func_array( $validationFunction, $arguments );		
	}
	
	/**
	 * Changes the invalid parameters to their default values, and changes their state to valid.
	 */
	public function correctInvalidParams() {
		foreach ( $this->invalid as $paramName => $paramValue ) {
			unset( $this->invalid[$paramName] );
			$this->valid[$paramName] = array_key_exists( 'default', $this->parameterInfo[$paramName] ) ? $this->parameterInfo[$paramName]['default'] : '';
			$this->setOutputType($this->valid[$paramName], $this->parameterInfo[$paramName]);
		}
	}	
	
	/**
	 * Ensures the type of the value is correct. 
	 * 
	 * @param $value
	 * @param array $info
	 */
	private function setOutputType(&$value, array $info) {
		// TODO: put code into functions linked by $outputFormats
		
		if (array_key_exists('output-type', $info)) {
			if (! is_array($info['output-type'])) $info['output-type'] = array($info['output-type']);
			
			switch(strtolower($info['output-type'][0])) {
				case 'list' :
					if (! is_array($value)) $value = array($value);
					
					$delimiter = count($info['output-type']) > 1 ? $info['output-type'][1] : ',';
					$wrapper = count($info['output-type']) > 2 ? $info['output-type'][2] : '';
					
					$value = $wrapper . implode($wrapper . $delimiter . $wrapper, $value) . $wrapper;
					break;
				case 'array' :
					if (! is_array($value)) $value = array($value);	
					break;
				case 'boolean' :
					if (is_array($value)) {
						$boolArray = array();
						foreach ($value as $item) $boolArray[] = $value == 'yes';
						$value = $boolArray;
					}
					else {
						$value = $value == 'yes';
					}
					break;
				case 'string' :
					if (is_array($value)) {
						global $wgLang;
						$value = $wgLang->listToText($value);
					}
			}			
		}
		else {
			
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
		self::$validationFunctions[$criteriaName] = $functionName;
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
		self::$listValidationFunctions[$criteriaName] = $functionName;
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
		self::$outputFormats[$criteriaName] = $functionName;
	}	
}
