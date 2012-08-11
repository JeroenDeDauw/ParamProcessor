<?php

namespace Validator\Test;

/**
 * Unit test for the BoolParam class.
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
class BoolParamTest extends ParamDefinitionTest {

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
				array( 'yes', true, true ),
				array( 'on', true, true ),
				array( '1', true, true ),
				array( 'no', true, false ),
				array( 'off', true, false ),
				array( '0', true, false ),
				array( 'foobar', false ),
				array( '2', false ),
				array( array(), false ),
				array( 42, false ),
			),
			'values' => array(
				array( '1', true, true ),
				array( 'yes', true, true ),
				array( 'no', false ),
				array( 'foobar', false ),
			),
		);
	}

	/**
	 * @see ParamDefinitionTest::getType
	 * @return string
	 */
	public function getType() {
		return 'boolean';
	}

}
