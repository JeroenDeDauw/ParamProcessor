<?php

namespace Validator\Test;
use ValidatorOptions;

/**
 * Unit test for the Validator\Options class.
 *
 * @file
 * @since 0.5
 *
 * @ingroup Validator
 * @ingroup Test
 *
 * @group Validator
 * @group ValidatorOptions
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorOptionsTest extends \MediaWikiTestCase {

	public function testConstructor() {
		$this->assertInstanceOf( '\ValidatorOptions', new ValidatorOptions() );
	}

	/**
	 * @return \ValidatorOptions
	 */
	protected function getInstance() {
		return new ValidatorOptions();
	}

	public function testBooleanSettersAndGetters() {
		$methods = array(
			'setUnknownInvalid' => 'getUnknownInvalid',
			'setLowercaseNames' => 'getLowercaseNames',
			'setRawStringInputs' => 'getRawStringInputs',
			'setTrimValues' => 'getTrimValues',
			'setLowercaseValues' => 'getLowercaseValues',
		);

		foreach ( $methods as $setter => $getter ) {
			$options = $this->getInstance();

			foreach ( array( false, true, false ) as $boolean ) {
				call_user_func_array( array( $options, $setter ), array( $boolean ) );

				$this->assertEquals( $boolean, call_user_func( array( $options, $getter ) ) );
			}
		}
	}

	public function testSetAndGetName() {
		$options = $this->getInstance();

		foreach ( array( 'foo', 'bar baz' ) as $name ) {
			$options->setName( $name );
			$this->assertEquals( $name, $options->getName() );
		}
	}

}
