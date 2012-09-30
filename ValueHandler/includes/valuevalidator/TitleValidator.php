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
	 * @var array
	 */
	protected $namespaces = array();

	/**
	 * Sets if there needs to be an article that has this title.
	 *
	 * @since 0.1
	 *
	 * @param boolean $hasToExist
	 */
	public function setHasToExist( $hasToExist ) {
		$this->hasToExist = $hasToExist;
	}

	/**
	 * Sets a whitelist of namespaces, ie a list into which the
	 * namespace of the title needs to be. You can provide an
	 * array of namespace integers or a single namespace int.
	 *
	 * @since 0.1
	 *
	 * @param array|int $namespaces
	 */
	public function setNamespaceRestriction( $namespaces ) {
		$this->namespaces = (array)$namespaces;
	}

	/**
	 * @see ValueValidator::doValidation
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 */
	public function doValidation( $value ) {
		/**
		 * @var Title $value
		 */
		if ( !$value instanceof Title ) {
			$this->addErrorMessage( 'Not a title' );
		}
		else {
			if ( $this->hasToExist && !$value->exists() ) {
				$this->addErrorMessage( 'Title does not exist' );
			}

			if ( $this->namespaces !== array() && !in_array( $value->getNamespace(), $this->namespaces ) ) {
				$this->addErrorMessage( 'Title not in the required namespace' );
			}
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

		if ( array_key_exists( 'namespaces', $options ) ) {
			$this->setNamespaceRestriction( $options['namespaces'] );
		}
	}

}
