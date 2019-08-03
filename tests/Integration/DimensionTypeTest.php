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
		yield [ '1 px', '1 px' ];
		yield [ '1 ex', '1 ex' ];
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

}
