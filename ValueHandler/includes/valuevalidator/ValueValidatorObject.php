<?php

/**
 * ValueValidator that holds base validation functions for any type of object.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 0.1
 *
 * @file
 * @ingroup ValueHandler
 * @ingroup ValueValidator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class ValueValidatorObject implements ValueValidator {

	/**
	 * A list of allowed values. This means the parameters value(s) must be in the list
	 * during validation. False for no restriction.
	 *
	 * @since 0.1
	 *
	 * @var array|false
	 */
	protected $allowedValues = false;

	/**
	 * A list of prohibited values. This means the parameters value(s) must
	 * not be in the list during validation. False for no restriction.
	 *
	 * @since 0.1
	 *
	 * @var array|false
	 */
	protected $prohibitedValues = false;

	/**
	 * @since 0.1
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * @since 0.1
	 *
	 * @var array of string
	 */
	private $errorMessages;

	/**
	 * @since 0.1
	 *
	 * @var array of ValueHandlerError
	 */
	private $errors;

	/**
	 * @see ValueValidator::validate
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 *
	 * @return ValueValidatorResult
	 */
	public final function validate( $value ) {
		$this->errorMessages = array();

		if ( $this->enableWhitelistRestrictions() ) {
			$this->valueIsAllowed( $value );
		}

		$this->doValidation( $value );

		if ( $this->errorMessages === array() ) {
			return ValueValidatorResultObject::newSuccess();
		}
		else {
			$errors = $this->errors;

			foreach ( $this->errorMessages as $errorMessage ) {
				$errors[] = ValueHandlerErrorObject::newError( $errorMessage );
			}

			return ValueValidatorResultObject::newError( $errors );
		}
	}

	/**
	 * Checks the value against the allowed values and prohibited values lists in case they are set.
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 */
	protected function valueIsAllowed( $value ) {
		if ( $this->allowedValues !== false && !in_array( $value, $this->allowedValues, true ) ) {
			$this->addErrorMessage( 'Value not in whitelist' );
		}

		if ( $this->prohibitedValues !== false && in_array( $value, $this->prohibitedValues, true ) ) {
			$this->addErrorMessage( 'Value in blacklist' );
		}
	}

	/**
	 * @see ValueValidator::validate
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 */
	public abstract function doValidation( $value );

	/**
	 * Sets the parameter definition values contained in the provided array.
	 * @see ParamDefinition::setArrayValues
	 *
	 * @since 0.1
	 *
	 * @param array $param
	 */
	public function setOptions( array $param ) {
		if ( $this->enableWhitelistRestrictions() ) {
			if ( array_key_exists( 'values', $param ) ) {
				$this->allowedValues = $param['values'];
			}

			if ( array_key_exists( 'excluding', $param ) ) {
				$this->prohibitedValues = $param['excluding'];
			}
		}

		$this->options = $param;
	}

	/**
	 * Registers an error message.
	 *
	 * @since 0.1
	 *
	 * @param string $errorMessage
	 */
	protected function addErrorMessage( $errorMessage ) {
		$this->errorMessages[] = $errorMessage;
	}

	/**
	 * Registers an error.
	 *
	 * @since 0.1
	 *
	 * @param ValueHandlerError $error
	 */
	protected function addError( ValueHandlerError $error ) {
		$this->errors[] = $error;
	}

	/**
	 * Registers a list of errors.
	 *
	 * @since 0.1
	 *
	 * @param $errors array of ValueHandlerError
	 */
	protected function addErrors( array $errors ) {
		$this->errors = array_merge( $this->errors, $errors );
	}

	/**
	 * Runs the value through the provided ValueValidator and registers the errors.
	 * Options of $this can be mapped to those of the passed ValueValidator using
	 * the $optionMap parameter in which keys are source names and values are target
	 * names.
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 * @param ValueValidator $validator
	 * @param string|null $property
	 * @param array $optionMap
	 */
	protected function runSubValidator( $value, ValueValidator $validator, $property = null, array $optionMap = array() ) {
		if ( $optionMap !== array() ) {
			$options = array();

			foreach ( $optionMap as $source => $target ) {
				if ( array_key_exists( $source, $this->options ) ) {
					$options[$target] = $this->options[$source];
				}
			}

			$validator->setOptions( $options );
		}

		/**
		 * @var ValueHandlerError $error
		 */
		foreach ( $validator->validate( $value )->getErrors() as $error ) {
			$this->addError( ValueHandlerErrorObject::newError( $error->getText(), $property ) );
		}
	}

	/**
	 * If the "values" and "excluding" arguments should be helf into account.
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	protected function enableWhitelistRestrictions() {
		return true;
	}

}
