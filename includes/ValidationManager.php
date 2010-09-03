<?php

/**
 * Class for parameter handling.
 *
 * @deprecated
 *
 * @file ValidationManager.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 * 
 * FIXME: missing required params should result in a no-go, no matter of the error level, as they can/are not defaulted.
 * TODO: make a distinction between fatal errors and regular errors by using 2 seperate error levels.
 */
class ValidationManager {

	/**
	 * @var Validator
	 */
	protected $validator;
	
	/**
	 * Parses and validates the provided parameters, and corrects them depending on the error level.
	 *
	 * @param array $rawParameters The raw parameters, as provided by the user.
	 * @param array $parameterInfo Array containing the parameter definitions, which are needed for validation and defaulting.
	 * @param array $defaultParams
	 * 
	 * @return array or false The valid parameters or false when the output should not be shown.
	 */
	public function manageParameters( array $rawParameters, array $parameterInfo, array $defaultParams = array() ) {
		global $egValidatorErrorLevel;

		$this->validator = new Validator();

		$this->validator->parseAndSetParams( $rawParameters, $parameterInfo, $defaultParams );
		$this->validator->validateAndFormatParameters();

		if ( $this->validator->hasErrors() && $egValidatorErrorLevel < Validator_ERRORS_STRICT ) {
			$this->validator->correctInvalidParams();
		}
		
		return !$this->validator->hasFatalError();
	}
	
	/**
	 * Validates the provided parameters, and corrects them depending on the error level.
	 * 
	 * @since 3.x
	 * 
	 * @param $parameters Array
	 * @param $parameterInfo Array
	 */
	public function manageParsedParameters( array $parameters, array $parameterInfo ) {
		global $egValidatorErrorLevel;
		
		$this->validator = new Validator();
		
		$this->validator->setParameters( $parameters, $parameterInfo );
		$this->validator->validateAndFormatParameters();
		
		if ( $this->validator->hasErrors() && $egValidatorErrorLevel < Validator_ERRORS_STRICT ) {
			$this->validator->correctInvalidParams();
		}
		
		return !$this->validator->hasFatalError();		
	}

	/**
	 * Returns an array with the valid parameters.
	 * 
	 * @since 3.x
	 * 
	 * @param boolean $includeMetaData
	 * 
	 * @return array
	 */
	public function getParameters( $includeMetaData = true ) {
		return $this->validator->getValidParams( $includeMetaData );
	}
	
}