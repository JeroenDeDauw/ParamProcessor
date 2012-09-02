<?php

/**
 * ValueValidator that validates a Title object.
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
class TitleValidator extends ValueValidatorObject {

	/**
	 * @since 0.1
	 * @var boolean
	 */
	protected $hasToExist = true;

	/**
	 * @since 0.1
	 * @param boolean $hasToExist
	 */
	public function setHasToExist( $hasToExist ) {
		$this->hasToExist = $hasToExist;
	}

	/**
	 * @see ValueValidator::doValidation
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 */
	public function doValidation( $value ) {
		$value = Title::newFromText( $value );

		if ( is_null( $value ) ) {
			$this->addErrorMessage( 'Not a valid title' );
		}
		elseif ( $this->hasToExist && !$value->exists() ) {
			$this->addErrorMessage( 'Title does not exist' );
		}
	}

	/**
	 * @see ValueValidator::setOptions
	 *
	 * @since 0.1
	 *
	 * @param array $options
	 */
	public function setOptions( array $options ) {
		parent::setOptions( $options );

		if ( array_key_exists( 'hastoexist', $options ) ) {
			$this->setHasToExist( $options['hastoexist'] );
		}
	}

}
