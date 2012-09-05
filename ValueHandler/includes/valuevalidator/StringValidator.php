<?php

/**
 * ValueValidator that validates a string value.
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
class StringValidator extends ValueValidatorObject {

	/**
	 * @see ValueValidatorObject::doValidation
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 */
	public function doValidation( $value ) {
		if ( !is_string( $value ) ) {
			$this->addErrorMessage( 'Not a string' );
			return;
		}

		$lowerBound = false;
		$upperBound = false;

		if ( array_key_exists( 'length', $this->options ) ) {
			$lowerBound = $this->options['length'];
			$upperBound = $this->options['length'];
		}
		else {
			if ( array_key_exists( 'minlength', $this->options ) ) {
				$lowerBound = $this->options['minlength'];
			}

			if ( array_key_exists( 'maxlength', $this->options ) ) {
				$upperBound = $this->options['maxlength'];
			}
		}

		if ( $lowerBound !== false || $upperBound !== false ) {
			$rangeValidator = new RangeValidator();
			$rangeValidator->setRange( $lowerBound, $upperBound );
			$this->runSubValidator( count( $value ), $rangeValidator, 'length' );
		}
	}

}
