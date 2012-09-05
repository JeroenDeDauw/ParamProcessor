<?php

/**
 * Interface for ValueHandler errors.
 * Immutable.
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
interface ValueHandlerError {

	const SEVERITY_ERROR = 9;
	const SEVERITY_WARNING = 4;

	/**
	 * Returns the error text.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getText();

	/**
	 * Returns the severity of the error
	 *
	 * @since 0.1
	 *
	 * @return integer, element of the ValueHandlerError::SEVERITY_ enum
	 */
	public function getSeverity();

	/**
	 * Returns the property of the value for which the error occurred, or null if it occurred for the value itself.
	 *
	 * @since 0.1
	 *
	 * @return string|null
	 */
	public function getProperty();

}