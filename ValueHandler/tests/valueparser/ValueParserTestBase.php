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
			$this->assertTypeOrValue( 'ValueHandlerError', $result->getError(), null );

			$this->assertException( function() use ( $result ) { $result->getValue(); } );
		}
	}

	/**
	 * Asserts that an exception of the specified type occurs when running
	 * the provided code.
	 *
	 * @since 0.1
	 *
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

	/**
	 * Asserts that the provided variable is of the specified
	 * internal type or equals the $value argument. This is useful
	 * for testing return types of functions that return a certain
	 * type or *value* when not set or on error.
	 *
	 * In core as of 1.20.
	 *
	 * @since 0.1
	 *
	 * @param string $type
	 * @param mixed $actual
	 * @param mixed $value
	 * @param string $message
	 */
	protected function assertTypeOrValue( $type, $actual, $value = false, $message = '' ) {
		if ( $actual === $value ) {
			$this->assertTrue( true, $message );
		}
		else {
			$this->assertType( $type, $actual, $message );
		}
	}

	/**
	 * Asserts the type of the provided value. This can be either
	 * in internal type such as boolean or integer, or a class or
	 * interface the value extends or implements.
	 *
	 * In core as of 1.20.
	 *
	 * @since 0.1
	 *
	 * @param string $type
	 * @param mixed $actual
	 * @param string $message
	 */
	protected function assertType( $type, $actual, $message = '' ) {
		// http://php.net/manual/en/function.gettype.php
		$internalTypes = array(
			'boolean',
			'integer',
			'double',
			'float',
			'string',
			'array',
			'resource',
			'NULL',
		);

		if ( in_array( gettype( $actual ), $internalTypes ) ) {
			$this->assertInternalType( $type, $actual, $message );
		}
		else {
			$this->assertInstanceOf( $type, $actual, $message );
		}
	}

}
