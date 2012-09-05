<?php

/**
 * Implementation of the value handler error interface.
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
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValueHandlerErrorObject implements ValueHandlerError {

	protected $text;
	protected $severity;
	protected $property;

	/**
	 * Create a new error.
	 *
	 * @since 0.1
	 *
	 * @param string $text
	 * @param string|null $property
	 *
	 * @return ValueHandlerError
	 */
	public static function newError( $text = '', $property = null ) {
		return new static( $text, ValueHandlerError::SEVERITY_ERROR, $property );
	}

	/**
	 * Create a new warning.
	 *
	 * @since 0.1
	 *
	 * @param string $text
	 * @param string|null $property
	 *
	 * @return ValueHandlerError
	 */
//	public static function newWarning( $text = '', $property = null ) {
//		return new static( $text, ValueHandlerError::SEVERITY_WARNING, $property );
//	}

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param string $text
	 * @param integer $severity
	 * @param string|null $property
	 */
	protected function __construct( $text, $severity, $property ) {
		$this->text = $text;
		$this->severity = $severity;
		$this->property = $property;
	}

	/**
	 * @see ValueHandlerError::getText
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @see ValueHandlerError::getSeverity
	 *
	 * @since 0.1
	 *
	 * @return integer
	 */
	public function getSeverity() {
		return $this->severity;
	}

	/**
	 * @see ValueHandlerError::getProperty
	 *
	 * @since 0.1
	 *
	 * @return string|null
	 */
	public function getProperty() {
		return $this->property;
	}

}
