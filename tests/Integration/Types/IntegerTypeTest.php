<?php

declare( strict_types = 1 );

namespace ParamProcessor\Tests\Integration\Types;

use ParamProcessor\ParameterTypes;

class IntegerTypeTest extends TypeTestBase {

	public function testPhpInteger() {
		$this->assertIntegerValidation( 42, 42 );
	}

	private function assertIntegerValidation( $expected, $input, array $definitionExtras = [] ) {
		$parameters = $this->process(
			[
				'amount' => array_merge(
					[
						'type' => ParameterTypes::INTEGER,
						'message' => 'test-message'
					],
					$definitionExtras
				)
			],
			[
				'amount' => $input,
			]
		)->getParameterArray();

		$this->assertSame( $expected, $parameters['amount'] );
	}

	public function testPhpIntegerDefaultsWhenExceedingUpperBound() {
		$this->assertIntegerValidation( 7, 42, [ 'upperbound' => 20, 'default' => 7 ] );
	}

	public function testPhpIntegerDefault() {
		$this->assertIntegerValidation( '7', 'NAN', [ 'default' => '7' ] );
	}

	public function testLowerBound() {
		$this->assertIntegerValidation( 7, '-1', [ 'lowerbound' => 0, 'default' => 7 ] );
	}

	public function testNegativeWithoutDefault() {
		$this->assertIntegerValidation( -1, '-1' );
	}

	public function testNegativeWithDefault() {
		$this->assertIntegerValidation( -1, '-1', [ 'default' => 7 ] );
	}

	public function testPhpIntegerWithDefault() {
		$this->assertIntegerValidation( 7, 42, [ 'default' => 7 ] );
	}

	public function testSmwOffsetDefinition() {
		$this->assertIntegerValidation(
			0,
			42,
			[
				'default' => 0,
				'negatives' => false,
				'upperbound' => 5000,
			]
		);
	}

}
