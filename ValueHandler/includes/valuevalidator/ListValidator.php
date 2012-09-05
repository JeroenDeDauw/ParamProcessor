<?php

/**
 * ValueValidator that validates a list of values.
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
class ListValidator extends ValueValidatorObject {

	/**
	 * @see ValueValidator::doValidation
	 *
	 * @since 0.1
	 *
	 * @param mixed $value
	 */
	public function doValidation( $value ) {
		if ( !is_array( $value ) ) {
			$this->addErrorMessage( 'Not an array' );
			return;
		}

		$optionMap = array(
			'elementcount' => 'range',
			'maxelements' => 'upperbound',
			'minelements' => 'lowerbound',
		);

		$this->runSubValidator( count( $value ), new RangeValidator(), 'length', $optionMap );
	}

	/**
	 * @see ValueValidatorObject::enableWhitelistRestrictions
	 *
	 * @since 0.1
	 *
	 * @return boolean
	 */
	protected function enableWhitelistRestrictions() {
		return false;
	}

}
