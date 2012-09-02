<?php

namespace ValueHandler\Test;

/**
 * Base for unit tests for ValueParser implementing classes.
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
abstract class ValueParserTestBase extends \MediaWikiTestCase {

	/**
	 * @since 0.1
	 */
	public abstract function parseProvider();

	/**
	 * @since 0.1
	 * @return string
	 */
	protected abstract function getParserClass();

	/**
	 * @since 0.1
	 * @return \ValueParser
	 */
	protected function getInstance() {
		$class = $this->getParserClass();
		return new $class();
	}

	/**
	 * @dataProvider parseProvider
	 * @since 0.1
	 * @param $value
	 * @param \ValueParserResult $expected
	 * @param \ValueParser|null $parser
	 */
	public function testParse( $value, \ValueParserResult $expected, \ValueParser $parser = null ) {
		if ( is_null( $parser ) ) {
			$parser = $this->getInstance();
		}

		$result = $parser->parse( $value );

		$this->assertEquals( $expected->isValid(), $result->isValid() );

		if ( $expected->isValid() ) {
			$this->assertEquals( $expected->getValue(), $result->getValue() );
			$this->assertNull( $result->getError() );
		}
		else {
			$this->assertInternalType( 'string', $result->getError() );

			$this->assertException( function() use ( $result ) { $result->getValue(); } );
		}
	}

	/**
	 * @param string $expected
	 * @param callable $code
	 */
	protected function assertException( $code, $expected = 'Exception' ) {
		$pokemons = null;

		try {
			call_user_func( $code );
		}
		catch ( \Exception $pokemons ) {
			// Gotta Catch 'Em All!
		}

		$this->assertInstanceOf( $expected, $pokemons );
	}

}
