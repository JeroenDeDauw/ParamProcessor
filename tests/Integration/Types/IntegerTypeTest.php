<?php

declare( strict_types = 1 );

namespace ParamProcessor\Tests\Integration\Types;

use ParamProcessor\ParameterTypes;

class IntegerTypeTest extends TypeTestBase {

	public function testNonStringValue() {
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

	public function testNonStringValueDefaultsWhenExceedingUpperBound() {
		$this->assertIntegerValidation( 7, 42, [ 'upperbound' => 20, 'default' => 7 ] );
	}

	public function testNonStringDefault() {
		$this->assertIntegerValidation( '7', 'NAN', [ 'default' => '7' ] );
	}

	public function testNoNegatives() {
		$this->assertIntegerValidation( 7, -1, [ 'negatives' => false, 'default' => 7 ] );
	}

	public function testNegative() {
		$this->assertIntegerValidation( -1, -1 );
	}

}
