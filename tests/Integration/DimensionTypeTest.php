<?php

declare( strict_types = 1 );

namespace ParamProcessor\Tests\Integration;

use ParamProcessor\ParamDefinitionFactory;
use ParamProcessor\ParameterTypes;
use ParamProcessor\ProcessingResult;
use ParamProcessor\Processor;
use PHPUnit\Framework\TestCase;

class DimensionTypeTest extends TestCase {

	/**
	 * @dataProvider widthProvider
	 */
	public function testWidth( string $input, string $expected ) {
		$parameters = $this->process(
			[
				'width' => [
					'type' => ParameterTypes::DIMENSION,
					'message' => 'test-message'
				]
			],
			[
				'width' => $input,
			]
		)->getParameterArray();

		$this->assertSame( $expected, $parameters['width'] );
	}

	public function widthProvider() {
		yield [ '10', '10px' ];
		yield [ '10px', '10px' ];
		yield [ '10%', '10%' ];
		yield [ '10em', '10em' ];
		yield [ '10ex', '10ex' ];
		yield [ 'auto', 'auto' ];
		yield [ ' 10 ', '10px' ];
		yield [ ' 1 ', '1px' ];
		yield [ '1 px', '1px' ];
		yield [ '1 ex', '1ex' ];
		// TODO: make sure unit is after the value
		// TODO: make sure only the unit is present
	}

	private function process( array $definitionArrays, array $userInput ): ProcessingResult {
		$processor = Processor::newDefault();

		$processor->setParameters( $userInput );
		$processor->setParameterDefinitions(
			ParamDefinitionFactory::newDefault()->newDefinitionsFromArrays( $definitionArrays )
		);

		return $processor->processParameters();
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
		// FIXME
//		yield [ '30%' ];
//		yield [ '31%' ];
//		yield [ '70%' ];
//		yield [ '69%' ];
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
