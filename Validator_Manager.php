<?php

/**
 * File holding the ValidatorManager class.
 *
 * @file ValidatorManager.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

/**
 * Class for parameter handling.
 *
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 * 
 * FIXME: missing required params should result in a no-go, no matter of the error level, as they can/are not defaulted.
 * 
 * TODO: make a distinction between fatal errors and regular errors by using 2 seperate error levels.
 */
final class ValidatorManager {

	private $errors = array();

	/**
	 * Validates the provided parameters, and corrects them depending on the error level.
	 *
	 * @param array $rawParameters The raw parameters, as provided by the user.
	 * @param array $parameterInfo Array containing the parameter definitions, which are needed for validation and defaulting.
	 * @param array $defaultParams
	 * 
	 * @return array or false The valid parameters or false when the output should not be shown.
	 */
	public function manageParameters( array $rawParameters, array $parameterInfo, array $defaultParams = array() ) {
		global $egValidatorErrorLevel;
 
		$validator = new Validator();

		$validator->parseAndSetParams( $rawParameters, $parameterInfo, $defaultParams );
		$hasNoErrors = $validator->validateAndFormatParameters();
		
		$hasFatalError = $hasNoErrors ? false : $this->hasFatalError();
		
		if ( !$hasNoErrors ) {
			if ( $egValidatorErrorLevel < Validator_ERRORS_STRICT ) $validator->correctInvalidParams();
			if ( $egValidatorErrorLevel >= Validator_ERRORS_WARN ) $this->errors = $validator->getErrors();
		}

		return !$hasFatalError ? $validator->getValidParams() : false;
	}

	/**
	 * Returns wether there are any fatal errors. Fatal errors are either missing or invalid required parameters,
	 * or simply any sort of error when the validation level is equal to (or bigger then) Validator_ERRORS_STRICT.
	 * 
	 * Note: This function assumes it will only get called when there are errors. It will always return true
	 * when $egValidatorErrorLevel is Validator_ERRORS_STRICT or above.
	 * 
	 * @return boolean
	 */
	private function hasFatalError() {
		global $egValidatorErrorLevel;
		$has = $egValidatorErrorLevel >= Validator_ERRORS_STRICT;
		
		if ( !$has ) {
			foreach ( $this->errors as $error ) {
				if ( $error['type'] == 'missing' ) {
					$has = true;
					break;
				}
			}
		}

		return $has;
	}
	
	/**
	 * Returns a string containing an HTML error list, or an empty string when there are no errors.
	 *
	 * @return string
	 */
	public function getErrorList() {
		global $wgLang, $egValidatorErrorLevel;
		$errorCount = count( $this->errors ) ;
		
		if ( $egValidatorErrorLevel >= Validator_ERRORS_SHOW && $errorCount > 0 ) {
			$errorList = '<b>' . wfMsgExt( 'validator_error_parameters', 'parsemag', $errorCount ) . '</b><br /><i>';

			$errors = array();

			foreach ( $this->errors as $error ) {
				$error['name'] = '<b>' . Sanitizer::escapeId( $error['name'] ) . '</b>';
				
				if ( $error['type'] == 'unknown' ) {
					$errors[] = wfMsgExt( 'validator_error_unknown_argument', array( 'parsemag' ), $error['name'] );
				}
				elseif ( $error['type'] == 'missing' ) {
					$errors[] = wfMsgExt( 'validator_error_required_missing', array( 'parsemag' ), $error['name'] );
				}
				elseif ( array_key_exists( 'list', $error ) && $error['list'] ) {
					switch( $error['type'] ) {
						case 'not_empty' :
							$msg = wfMsgExt( 'validator_list_error_empty_argument', array( 'parsemag' ), $error['name'] );
							break;
						case 'in_range' :
							$msg = wfMsgExt( 'validator_list_error_invalid_range', array( 'parsemag' ), $error['name'], '<b>' . $error['args'][0] . '</b>', '<b>' . $error['args'][1] . '</b>' );
							break;
						case 'is_numeric' :
							$msg = wfMsgExt( 'validator_list_error_must_be_number', array( 'parsemag' ), $error['name'] );
							break;
						case 'is_integer' :
							$msg = wfMsgExt( 'validator_list_error_must_be_integer', array( 'parsemag' ), $error['name'] );
							break;
						case 'in_array' :
							$itemsText = $wgLang->listToText( $error['args'] );
							$msg = wfMsgExt( 'validator_error_accepts_only', array( 'parsemag' ), $error['name'], $itemsText, count( $error['args'] ) );
							break;
						case 'invalid' : default :
							$msg = wfMsgExt( 'validator_list_error_invalid_argument', array( 'parsemag' ), $error['name'] );
							break;
					}
					
					if ( array_key_exists( 'invalid-items', $error ) ) {
						$omitted = array();
						foreach ( $error['invalid-items'] as $item ) $omitted[] = Sanitizer::escapeId( $item );
						$msg .= ' ' . wfMsgExt( 'validator_list_omitted', array( 'parsemag' ),
							$wgLang->listToText( $omitted ), count( $omitted ) );
					}
					
					$errors[] = $msg;
				}
				else {
					switch( $error['type'] ) {
						case 'not_empty' :
							$errors[] = wfMsgExt( 'validator_error_empty_argument', array( 'parsemag' ), $error['name'] );
							break;
						case 'in_range' :
							$errors[] = wfMsgExt( 'validator_error_invalid_range', array( 'parsemag' ), $error['name'], '<b>' . $error['args'][0] . '</b>', '<b>' . $error['args'][1] . '</b>' );
							break;
						case 'is_numeric' :
							$errors[] = wfMsgExt( 'validator_error_must_be_number', array( 'parsemag' ), $error['name'] );
							break;
						case 'is_integer' :
							$errors[] = wfMsgExt( 'validator_error_must_be_integer', array( 'parsemag' ), $error['name'] );
							break;
						case 'in_array' :
							$itemsText = $wgLang->listToText( $error['args'] );
							$errors[] = wfMsgExt( 'validator_error_accepts_only', array( 'parsemag' ), $error['name'], $itemsText, count( $error['args'] ) );
							break;
						case 'invalid' : default :
							$errors[] = wfMsgExt( 'validator_error_invalid_argument', array( 'parsemag' ), '<b>' . Sanitizer::escapeId( $error['value'] ) . '</b>', $error['name'] );
							break;
					}
				}
			}

			return $errorList . implode( $errors, '<br />' ) . '</i><br />';
		}
		elseif ( $egValidatorErrorLevel == Validator_ERRORS_WARN && $errorCount > 0 ) {
			return '<b>' . wfMsgExt( 'validator_warning_parameters', array( 'parsemag' ), $errorCount ) . '</b>';
		}
		else {
			return '';
		}
	}
}
