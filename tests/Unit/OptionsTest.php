<?php

declare( strict_types = 1 );

namespace ParamProcessor\Tests\Unit;

use ParamProcessor\Options;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ParamProcessor\Options
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class OptionsTest extends TestCase {

	public function testBooleanSettersAndGetters() {
		$methods = [
			'setUnknownInvalid' => 'unknownIsInvalid',
			'setLowercaseNames' => 'lowercaseNames',
			'setRawStringInputs' => 'isStringlyTyped',
			'setTrimNames' => 'trimNames',
			'setTrimValues' => 'trimValues',
			'setLowercaseValues' => 'lowercaseValues',
		];

		foreach ( $methods as $setter => $getter ) {
			$options = new Options();

			foreach ( [ false, true, false ] as $boolean ) {
				call_user_func_array( [ $options, $setter ], [ $boolean ] );

				$this->assertEquals( $boolean, call_user_func( [ $options, $getter ] ) );
			}
		}
	}

	public function testSetAndGetName() {
		$options = new Options();

		foreach ( [ 'foo', 'bar baz' ] as $name ) {
			$options->setName( $name );
			$this->assertEquals( $name, $options->getName() );
		}
	}

}
