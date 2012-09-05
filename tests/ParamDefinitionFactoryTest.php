<?php

namespace Validator\Test;
use ParamDefinitionFactory;

/**
 * Unit test for the Validator\Options class.
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
 * @ingroup Validator
 * @ingroup Test
 *
 * @group Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinitionFactoryTest extends \MediaWikiTestCase {

	public function testConstructor() {
		$this->assertInstanceOf( '\ParamDefinitionFactory', ParamDefinitionFactory::singleton() );
		$this->assertTrue( ParamDefinitionFactory::singleton() === ParamDefinitionFactory::singleton() );
	}

	public function classProvider() {
		$classes = array(
			'ParamDefinition',
		);

		return $this->arrayWrap( $classes );
	}

	/**
	 * @dataProvider classProvider
	 * @param string $class
	 */
	public function testGetType( $class ) {
		$this->assertTypeOrValue( 'string', ParamDefinitionFactory::singleton()->getType( $class ) );
	}

	protected function assertTypeOrValue( $type, $actual, $value = false ) {
		if ( $actual === $value ) {
			$this->assertTrue( true );
		}
		else {
			$this->assertInternalType( $type, $actual );
		}
	}

	public function testRegisterType() {
		$factory = ParamDefinitionFactory::singleton();

		$this->assertTrue( $factory->registerType( 'foobarbaz', array() ) );
		$this->assertFalse( $factory->registerType( 'foobarbaz', array() ) );
	}

}
