<?php

namespace Validator\Test;
use Validator, ValidatorOptions;

/**
 * Unit test for the Validator class.
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
 * @since 0.5
 *
 * @ingroup Validator
 * @ingroup Test
 *
 * @group Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorTest extends \MediaWikiTestCase {

	public function testConstructor() {
		$this->assertInstanceOf( '\Validator', new Validator() );
	}

	public function newFromOptionsProvider() {
		$options = array();

		$option = new ValidatorOptions();

		$options[] = clone $option;

		$option->setName( 'foobar' );
		$option->setLowercaseNames( false );

		$options[] = clone $option;

		return $this->arrayWrap( $options );
	}

	protected function arrayWrap( array $elements ) {
		return array_map(
			function( $element ) {
				return array( $element );
			},
			$elements
		);
	}

	public function testNewFromOptions() {
		$options = new ValidatorOptions();
		$validator = Validator::newFromOptions( clone $options );
		$this->assertInstanceOf( '\Validator', $validator );
		$this->assertEquals( $options, $validator->getOptions() );
	}

	protected function getSimpleParams() {
		$params = array(
			'awesome' => 'yes',
			'howmuch' => '9001',
			'float' => '4.2',
			'page' => 'Ohi there!',
		);

		$definitions = array(
			'awesome' => array(
				'type' => 'boolean',
			),
			'howmuch' => array(
				'type' => 'integer',
			),
			'float' => array(
				'type' => 'float',
			),
			'page' => array(
				'type' => 'title',
				'hastoexist' => false,
			),
		);

		$options = new ValidatorOptions();

		$expected = array(
			'awesome' => true,
			'howmuch' => 9001,
			'float' => 4.2,
			'page' => \Title::newFromText( 'Ohi there!' ),
		);

		return array( $params, $definitions, $options, $expected );
	}

	public function parameterProvider() {
		// $params, $definitions [, $options, $expected]
		$argLists = array();

		$argLists[] = $this->getSimpleParams();

		$argLists[] = $this->getSimpleParams();

		foreach ( $argLists as &$argList ) {
			foreach ( $argList[1] as $key => &$definition ) {
				$definition['message'] = 'test-' . $key;
			}

			if ( !array_key_exists( 2, $argList ) ) {
				$argList[2] = new ValidatorOptions();
			}
		}

		return $argLists;
	}

	/**
	 * @dataProvider parameterProvider
	 */
	public function testSetParameters( array $params, array $definitions, ValidatorOptions $options, array $expected = array() ) {
		$validator = Validator::newFromOptions( $options );

		$validator->setParameters( $params, $definitions );

		$this->assertTrue( true ); // TODO
	}

	/**
	 * @dataProvider parameterProvider
	 */
	public function testValidateParameters( array $params, array $definitions, ValidatorOptions $options, array $expected = array() ) {
		$validator = Validator::newFromOptions( $options );

		$validator->setParameters( $params, $definitions );

		$validator->validateParameters();

		$this->assertArrayEquals( $expected, $validator->getParameterValues(), false, true );
	}

}
