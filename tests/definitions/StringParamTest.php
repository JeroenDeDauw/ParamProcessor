<?php

/**
 * Unit test for the StringParam class.
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
class StringParamTest extends ParamDefinitionTest {

	/**
	 * @see ParamDefinitionTest::getDefinitions
	 */
	public function getDefinitions() {
		$params = parent::getDefinitions();



		return $params;
	}

	/**
	 * @see ParamDefinitionTest::valueProvider
	 */
	public function valueProvider() {
		return array(
			'empty' => array(
				array( 'ohi there', true, 'ohi there' ),
				array( 4.2, false ),
				array( array( 42 ), false ),
			),
			'values' => array(
				array( 'foo', true, 'foo' ),
				array( '1', true, '1' ),
				array( 'yes', true, 'yes' ),
				array( true, false ),
				array( 0.1, false ),
				array( array(), false ),
			),
		);
	}

	/**
	 * @see ParamDefinitionTest::getType
	 */
	public function getType() {
		return 'string';
	}

}
