<?php

/**
 * Interface for value validators.
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
interface ValueValidator {

	/**
	 * Parses a value.
	 *
	 * @since 0.1
	 *
	 * @param mixed $value The value to validate
	 *
	 * @return ValueValidatorResult
	 */
	public function validate( $value );

	/**
	 * Takes an associative array with options and sets those known to the ValueValidator.
	 *
	 * @since 0.1
	 *
	 * @param array $options
	 */
	public function setOptions( array $options );

}