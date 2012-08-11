<?php

namespace Validator\Test;

/**
 * Unit test for the CharParam class.
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
class CharParamTest extends ParamDefinitionTest {

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
				array( 'a', true, 'a' ),
				array( '0', true, '0' ),
				array( 'abc', false ),
				array( 42, false ),
				array( 4, false ),
			),
			'values' => array(
				array( '1', true, '1' ),
				array( 'yes', false ),
				array( 'no', false ),
				array( '0.1', false ),
			),
		);
	}

	/**
	 * @see ParamDefinitionTest::getType
	 * @return string
	 */
	public function getType() {
		return 'char';
	}

}
