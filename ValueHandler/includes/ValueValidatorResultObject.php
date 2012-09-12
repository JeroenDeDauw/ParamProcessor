<?php

/**
 * Implementation of the value validator result interface.
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
class ValueValidatorResultObject implements  ValueValidatorResult {

	/**
	 * @since 0.1
	 *
	 * @var boolean
	 */
	protected $isValid;

	/**
	 * @since 0.1
	 *
	 * @var array of ValueHandlerError
	 */
	protected $errors = array();

	/**
	 * @since 0.1
	 *
	 * @return ValueValidatorResult
	 */
	public static function newSuccess() {
		return new static( true );
	}

	/**
	 * @since 0.1
	 *
	 * @param $errors array of ValueHandlerError
	 *
	 * @return ValueValidatorResult
	 */
	public static function newError( array $errors ) {
		return new static( false, $errors );
	}

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param boolean $isValid
	 * @param $errors array of ValueHandlerError
	 */
	protected function __construct( $isValid, array $errors = array() ) {
		$this->isValid = $isValid;
		$this->errors = $errors;
	}

	/**
	 * @see ValueParserResult::isValid
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->isValid;
	}

	/**
	 * @see ValueParserResult::getError
	 *
	 * @since 0.1
	 *
	 * @return array of ValueHandlerError
	 */
	public function getErrors() {
		return $this->errors;
	}

}
