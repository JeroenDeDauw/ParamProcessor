<?php

namespace ValueHandler\Test;

/**
 * Unit test StringValueParser class.
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
 * @file
 * @since 1.0
 *
 * @ingroup ValueHandler
 * @ingroup Test
 *
 * @group ValueHandler
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class StringValueParserTest extends ValueParserTestBase {

	/**
	 * @see ValueParserTestBase::parseProvider
	 *
	 * @since 1.0
	 */
	public function parseProvider() {
		$argLists = array();

		$invalid = array(
			true,
			false,
			null,
			4.2,
			array(),
			42,
		);

		foreach ( $invalid as $value ) {
			$argLists[] = array( $value, \ValueParserResultObject::newError( '' ) );
		}

		return $argLists;
	}

}
