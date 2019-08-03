<?php

namespace ParamProcessor\Tests\Unit;

use ParamProcessor\ProcessedParam;
use ParamProcessor\ProcessingError;
use ParamProcessor\ProcessingResult;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ParamProcessor\ProcessingResult
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ProcessingResultTest extends TestCase {

	public function testGetParameters() {
		$processedParams = [
			$this->createMock( ProcessedParam::class )
		];

		$result = new ProcessingResult( $processedParams );

		$this->assertEquals( $processedParams, $result->getParameters() );
	}

	public function testGetErrors() {
		$errors = [
			$this->createMock( ProcessingError::class )
		];

		$result = new ProcessingResult( [], $errors );

		$this->assertEquals( $errors, $result->getErrors() );
	}

	public function testGivenNoErrors_HasNoFatal() {
		$this->assertNoFatalForErrors( [] );
	}

	private function assertNoFatalForErrors( array $errors ) {
		$result = new ProcessingResult( [], $errors );

		$this->assertFalse( $result->hasFatal() );
	}

	public function testGivenNonfatalErrors_HasNoFatal() {
		$this->assertNoFatalForErrors( [
			new ProcessingError( '', ProcessingError::SEVERITY_HIGH ),
			new ProcessingError( '', ProcessingError::SEVERITY_LOW ),
			new ProcessingError( '', ProcessingError::SEVERITY_MINOR ),
			new ProcessingError( '', ProcessingError::SEVERITY_NORMAL ),
		] );
	}

	public function testGivenFatalError_HasFatal() {
		$result = new ProcessingResult( [], [
			new ProcessingError( '', ProcessingError::SEVERITY_HIGH ),
			new ProcessingError( '', ProcessingError::SEVERITY_LOW ),
			new ProcessingError( '', ProcessingError::SEVERITY_FATAL ),
			new ProcessingError( '', ProcessingError::SEVERITY_MINOR ),
			new ProcessingError( '', ProcessingError::SEVERITY_NORMAL ),
		] );

		$this->assertTrue( $result->hasFatal() );
	}

	public function testGetParameterArrayWithNoParameters() {
		$this->assertSame(
			[],
			( new ProcessingResult( [] ) )->getParameterArray()
		);
	}

	public function testGetParameterArray() {
		$this->assertSame(
			[
				'first' => 42,
				'second' => 23,
			],
			( new ProcessingResult( [
				new ProcessedParam( 'first', 42, false ),
				new ProcessedParam( 'second', 23, true )
			] ) )->getParameterArray()
		);
	}

}