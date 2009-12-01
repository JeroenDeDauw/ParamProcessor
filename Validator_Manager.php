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
 */
final class ValidatorManager {

	private $errors = array();

	/**
	 * Validates the provided parameters, and corrects them depedning on the error level.
	 *
	 * @param array $rawParameters
	 * @param array $parameterInfo
	 *
	 * @return boolean Indicates whether the regular output should be shown or not.
	 */
	public function manageMapparameters( array $rawParameters, array $parameterInfo ) {
		global $egValidatorErrorLevel;

		$validator = new Validator();

		$validator->setParameterInfo( $parameterInfo );
		$validator->setParameters( $rawParameters );

		if ( ! $validator->validateParameters() ) {
			if ( $egValidatorErrorLevel != Validator_ERRORS_STRICT ) $validator->correctInvalidParams();
			if ( $egValidatorErrorLevel >= Validator_ERRORS_SHOW ) $this->errors = $validator->getErrors();
		}

		$showOutput = ! ( $egValidatorErrorLevel == Validator_ERRORS_STRICT && count( $this->errors ) > 0 );

		return $showOutput ? $validator->getValidParams() : false;
	}

	/**
	 * Returns a string containing an HTML error list, or an empty string when there are no errors.
	 *
	 * @return string
	 */
	public function getErrorList() {
		global $wgLang;
		global $egValidatorErrorLevel;

		$error_count = count( $this->errors ) ;
		if ( $egValidatorErrorLevel >= Validator_ERRORS_SHOW && $error_count > 0 ) {
			$errorList = '<b>' . wfMsgExt( 'validator_error_parameters', 'parsemag', $error_count ) . ':</b><br /><i>';

			$errors = array();

			foreach ( $this->errors as $error ) {
				$error['name'] = '</i>' . $error['name'] . '<i>';
				switch( $error['error'][0] ) {
					// General errors
					case 'unknown' :
						$errors[] = wfMsgExt( 'validator_error_unknown_argument', array( 'parsemag' ), $error['name'] );
						break;
					case 'missing' :
						$errors[] = wfMsgExt( 'validator_error_required_missing', array( 'parsemag' ), $error['name'] );
						break;
					// Specific validation faliures
					case 'not_empty' :
						$errors[] = wfMsgExt( 'validator_error_empty_argument', array( 'parsemag' ), $error['name'] );
						break;
					case 'in_range' :
						$errors[] = wfMsgExt( 'validator_error_invalid_range', array( 'parsemag' ), $error['name'], $error['error'][1][0], $error['error'][1][1] );
						break;
					case 'is_numeric' :
						$errors[] = wfMsgExt( 'validator_error_must_be_number', array( 'parsemag' ), $error['name'] );
						break;
					case 'in_array' : case 'all_in_array' :  case 'all_str_in_array' :
						$items = $error['error'][0] == 'all_str_in_array' ? $error['error'][1][1] : $error['error'][1];
						$itemsText = $wgLang->listToText( $items );
						$errors[] = wfMsgExt( 'maps_error_accepts_only', array( 'parsemag' ), $error['name'], $itemsText, count( $items ) );
						break;
					// Unspesified errors
					case 'invalid' : default :
						$errors[] = wfMsgExt( 'validator_error_invalid_argument', array( 'parsemag' ), $error['error'][2], $error['name'] );
						break;
				}
			}

			return $errorList . implode( $errors, '<br />' ) . '</i><br />';
		}
		else {
			return '';
		}
	}
}
