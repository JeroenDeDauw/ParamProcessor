<?php

declare( strict_types = 1 );

namespace ParamProcessor\Tests\Integration\Types;

use ParamProcessor\ParameterTypes;

class DimensionTypeTest extends TypeTestBase {

	/**
	 * @dataProvider validInputProvider
	 */
	public function testValidInput( string $input, string $expected ) {
		$parameters = $this->process(
			[
				'width' => [
					'type' => ParameterTypes::DIMENSION,
					'units' => [ 'px', 'ex', 'em', '%' ],
					'message' => 'test-message'
				]
			],
			[
				'width' => $input,
			]
		)->getParameterArray();

		$this->assertSame( $expected, $parameters['width'] );
	}

	public function validInputProvider() {
		yield [ '10', '10px' ];
		yield [ '10px', '10px' ];
		yield [ '10%', '10%' ];
		yield [ '10em', '10em' ];
		yield [ '10ex', '10ex' ];
		yield [ ' 10 ', '10px' ];
		yield [ ' 1 ', '1px' ];
		yield [ '1 px', '1px' ];
		yield [ '1 ex', '1ex' ];
		// TODO: make sure unit is after the value
		// TODO: make sure only the unit is present
	}

	/**
	 * @dataProvider validInputProvider
	 */
	public function testValidInputWhenDefaultIsDefined( string $input, string $expected ) {
		$parameters = $this->process(
			[
				'width' => [
					'type' => ParameterTypes::DIMENSION,
					'units' => [ 'px', 'ex', 'em', '%' ],
					'default' => '1337px',
					'message' => 'test-message'
				]
			],
			[
				'width' => $input,
			]
		)->getParameterArray();

		$this->assertSame( $expected, $parameters['width'] );
	}

	/**
	 * @dataProvider heightProvider
	 */
	public function testHeight( string $input, string $expected ) {
		$parameters = $this->process(
			[
				'height' => [
					'type' => ParameterTypes::DIMENSION,
					'message' => 'test-message'
				]
			],
			[
				'height' => $input,
			]
		)->getParameterArray();

		$this->assertSame( $expected, $parameters['height'] );
	}

	public function heightProvider() {
		yield [ '10', '10px' ];
		yield [ '10px', '10px' ];
		yield [ '10em', '10em' ];
		yield [ '10ex', '10ex' ];
	}

	public function testAlternateDefaultUnit() {
		$parameters = $this->process(
			[
				'height' => [
					'type' => ParameterTypes::DIMENSION,
					'defaultunit' => '%',
					'message' => 'test-message'
				]
			],
			[
				'height' => '2.5',
			]
		)->getParameterArray();

		$this->assertSame( '2.5%', $parameters['height'] );
	}

	/**
	 * @dataProvider invalidDimensionProvider
	 */
	public function testInvalidInputsDefault( string $invalidDimension ) {
		$parameters = $this->process(
			[
				'height' => [
					'type' => ParameterTypes::DIMENSION,
					'default' => '42%',
					'lowerbound' => 20,
					'upperbound' => 80,
					'minpercentage' => 30,
					'maxpercentage' => 70,
					'message' => 'test-message'
				]
			],
			[
				'height' => $invalidDimension,
			]
		)->getParameterArray();

		$this->assertSame( '42%', $parameters['height'] );
	}

	public function invalidDimensionProvider() {
		yield [ 'invalid' ];
		yield [ 'px' ];
		yield [ '19' ];
		yield [ '81' ];
		yield [ '29%' ];
		yield [ '71%' ];
		yield 'auto not allowed' => [ 'auto' ];
		yield 'unit not allowed' => [ '1 wtf' ];
	}

	/**
	 * @dataProvider validBoundsInput
	 */
	public function testValidInputWithBounds( string $valid ) {
		$parameters = $this->process(
			[
				'height' => [
					'type' => ParameterTypes::DIMENSION,
					'units' => [ 'px', '%' ],
					'default' => '42%',
					'lowerbound' => 20,
					'upperbound' => 80,
					'minpercentage' => 30,
					'maxpercentage' => 70,
					'message' => 'test-message'
				]
			],
			[
				'height' => $valid,
			]
		)->getParameterArray();

		$this->assertSame( $valid, $parameters['height'] );
	}

	public function validBoundsInput() {
		yield [ '20px' ];
		yield [ '21px' ];
		yield [ '80px' ];
		yield [ '79px' ];
		yield [ '30%' ];
		yield [ '31%' ];
		yield [ '70%' ];
		yield [ '69%' ];
	}

	public function testAllowAuto() {
		$parameters = $this->process(
			[
				'height' => [
					'type' => ParameterTypes::DIMENSION,
					'allowauto' => true,
					'message' => 'test-message'
				]
			],
			[
				'height' => 'auto',
			]
		)->getParameterArray();

		$this->assertSame( 'auto', $parameters['height'] );
	}

	public function testCanUseSpecialUnit() {
		$parameters = $this->process(
			[
				'height' => [
					'type' => ParameterTypes::DIMENSION,
					'units' => [ 'wtf' ],
					'message' => 'test-message'
				]
			],
			[
				'height' => '4.2 wtf',
			]
		)->getParameterArray();

		$this->assertSame( '4.2wtf', $parameters['height'] );
	}

}
