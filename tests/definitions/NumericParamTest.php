<?php

namespace Validator\Test;

/**
 * Unit test for the NumericParam class.
 *
 * @file
 * @since 0.5
 *
 * @ingroup Validator
 * @ingroup Test
 *
 * @group Validator
 * @group ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class NumericParamTest extends ParamDefinitionTest {

	public function lowerBoundProvider() {
		return array(
			array( 42, 42, true ),
			array( 42, 41, false ),
			array( 42, 43, true ),
			array( false, 43, true ),
			array( false, 0, true ),
			array( false, -100, true ),
			array( -100, -100, true ),
			array( -99, -100, false ),
			array( -101, -100, true ),
		);
	}

	/**
	 * @dataProvider lowerBoundProvider
	 */
	public function testSetLowerBound( $bound, $testValue, $validity ) {
		/**
		 * @var \NumericParam $definition
		 */
		$definition = $this->getEmptyInstance();
		$definition->setLowerBound( $bound );

		$this->validate( $definition, $testValue, $validity );
	}

	public function upperBoundProvider() {
		return array(
			array( 42, 42, true ),
			array( 42, 41, true ),
			array( 42, 43, false ),
			array( false, 43, true ),
			array( false, 0, true ),
			array( false, -100, true ),
			array( -100, -100, true ),
			array( -99, -100, true ),
			array( -101, -100, false ),
		);
	}

	/**
	 * @dataProvider upperBoundProvider
	 */
	public function testSetUpperBound( $bound, $testValue, $validity ) {
		/**
		 * @var \NumericParam $definition
		 */
		$definition = $this->getEmptyInstance();
		$definition->setUpperBound( $bound );

		$this->validate( $definition, $testValue, $validity );
	}

}
