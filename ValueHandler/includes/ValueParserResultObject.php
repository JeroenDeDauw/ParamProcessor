<?php

/**
 * Implementation of the value parser result interface.
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
 * @since 1.0
 *
 * @file
 * @ingroup ValueHandler
 * @ingroup ValueParser
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValueParserResultObject implements  ValueParserResult {

	/**
	 * @since 1.0
	 *
	 * @var boolean
	 */
	protected $isValid;

	/**
	 * @since 1.0
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * @since 1.0
	 *
	 * @var string|null
	 */
	protected $error;

	/**
	 * @since 0.1
	 *
	 * @param mixed $value
	 *
	 * @return ValueParserResult
	 */
	public static function newSuccess( $value ) {
		return new static( true, $value );
	}

	/**
	 * @since 0.1
	 *
	 * @param string $errorMessage
	 *
	 * @return ValueParserResult
	 */
	public static function newError( $errorMessage ) {
		return new static( false, null, $errorMessage );
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param boolean $isValid
	 * @param mixed $value
	 * @param string|null $errorMessage
	 */
	protected function __construct( $isValid, $value = null, $errorMessage = null ) {
		$this->isValid = $isValid;
		$this->value = $value;
		$this->error = $errorMessage;
	}

	/**
	 * @see ValueParserResult::getValue
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function getValue() {
		if ( $this->isValid() ) {
			return $this->value;
		}
		else {
			throw new Exception( 'Cannot obtain the value of the parsing result as the parser got invalid input' );
		}
	}

	/**
	 * @see ValueParserResult::isValid
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function isValid() {
		return $this->isValid;
	}

	/**
	 * @see ValueParserResult::getError
	 *
	 * @since 1.0
	 *
	 * @return string|null
	 */
	public function getError() {
		return $this->error;
	}

}
