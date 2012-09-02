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
class ValueValidatorObject implements ValueValidator {

	/**
	 * A list of allowed values. This means the parameters value(s) must be in the list
	 * during validation. False for no restriction.
	 *
	 * @since 1.0
	 *
	 * @var array|false
	 */
	protected $allowedValues = false;

	/**
	 * A list of prohibited values. This means the parameters value(s) must
	 * not be in the list during validation. False for no restriction.
	 *
	 * @since 1.0
	 *
	 * @var array|false
	 */
	protected $prohibitedValues = false;

	/**
	 * @since 0.1
	 *
	 * @var array of string
	 */
	private $errorMessages;

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

		$this->valueIsAllowed( $value );

		$this->doValidation( $value );

		if ( $this->errorMessages === array() ) {
			return ValueValidatorResultObject::newSuccess();
		}
		else {
			$errors = array();

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
		if ( array_key_exists( 'values', $param ) ) {
			$this->allowedValues = $param['values'];
		}

		if ( array_key_exists( 'excluding', $param ) ) {
			$this->prohibitedValues = $param['excluding'];
		}
	}

	/**
	 *
	 *
	 * @since 0.1
	 *
	 * @param string $errorMessage
	 */
	protected function addErrorMessage( $errorMessage ) {
		return $this->errorMessages[] = $errorMessage;
	}

}
