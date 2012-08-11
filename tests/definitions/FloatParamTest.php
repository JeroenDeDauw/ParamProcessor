<?php

namespace Validator\Test;

/**
 * Unit test for the FloatParam class.
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
class FloatParamTest extends NumericParamTest {

	/**
	 * @see ParamDefinitionTest::getDefinitions
	 * @return array
	 */
	public function getDefinitions() {
		$params = parent::getDefinitions();

		return $params;
	}

	/**
	 * @see ParamDefinitionTest::valueProvider
	 * @return array
	 */
	public function valueProvider() {
		return array(
			'empty' => array(
				array( '1', true, 1 ),
				array( '1.1', true, 1.1 ),
				array( '0.2555', true, 0.2555 ),
				array( '1.1.1', false ),
				array( 'foobar', false ),
				array( array(), false ),
				array( 'yes', false ),
				array( false, false ),
			),
			'values' => array(
				array( '1', true, 1 ),
				array( 'yes', false ),
				array( 'no', false ),
				array( '0.1', true, 0.1 ),
				array( '0.2555', false ),
			),
		);
	}

	/**
	 * @see ParamDefinitionTest::getType
	 * @return string
	 */
	public function getType() {
		return 'float';
	}

}
