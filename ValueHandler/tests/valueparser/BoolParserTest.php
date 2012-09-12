<?php

namespace ValueHandler\Test;

/**
 * Unit test BoolParser class.
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
 * @since 0.1
 *
 * @ingroup ValueHandler
 * @ingroup Test
 *
 * @group ValueHandler
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class BoolParserTest extends StringValueParserTest {

	/**
	 * @see ValueParserTestBase::parseProvider
	 *
	 * @since 0.1
	 */
	public function parseProvider() {
		$argLists = array();

		$valid = array(
			'yes' => true,
			'on' => true,
			'1' => true,
			'true' => true,
			'no' => false,
			'off' => false,
			'0' => false,
			'false' => false,
		);

		foreach ( $valid as $value => $expected ) {
			$argLists[] = array( (string)$value, \ValueParserResultObject::newSuccess( $expected ) );
		}

		$invalid = array(
			'foo',
			'2',
		);

		foreach ( $invalid as $value ) {
			$argLists[] = array( $value, \ValueParserResultObject::newErrorText( '' ) );
		}

		return array_merge( $argLists, parent::parseProvider() );
	}

	/**
	 * @see ValueParserTestBase::getParserClass
	 * @since 0.1
	 * @return string
	 */
	protected function getParserClass() {
		return 'BoolParser';
	}

}
