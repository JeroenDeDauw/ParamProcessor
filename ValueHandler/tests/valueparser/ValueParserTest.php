<?php

namespace ValueHandler\Test;

/**
 * Unit test for the implementation of the ValueParser interface.
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
class ValueParserTest extends \MediaWikiTestCase {

//	protected function getImplementingClasses( $interfaceName ) {
//		foreach ( array_keys( $GLOBALS['wgAutoloadClasses'] ) as $className ) {
//			class_exists( $className, true );
//		}
//
//		return array_filter(
//			get_declared_classes(),
//			function( $className ) use ( $interfaceName ) {
//				return in_array( $interfaceName, class_implements( $className, true ) );
//			}
//		);
//	}

	public function instanceProvider() {
		return array_map(
			function( $className ) {
				return array( new $className() );
			},
			array(
				'NullParser'
			)
		);
	}

	/**
	 * @dataProvider instanceProvider
	 */
	public function testParser( \ValueParser $parser ) {
		foreach ( array( 'foo', 42, array(), false, 'ohi there!' ) as $value ) {
			$this->assertInstanceOf( 'ValueParserResult', $parser->parse( $value ) );
		}
	}

}
