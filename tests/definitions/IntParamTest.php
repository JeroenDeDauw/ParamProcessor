<?php

/**
 * Unit test for the IntParamTest class.
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
class IntParamTest extends ParamDefinitionTest {

	public function definitionProvider() {
		$params = array();

		$params['count'] = array(
			'type' => 'integer',
		);

		$params['amount'] = array(
			'type' => 'integer',
			'default' => 42,
			'upperbound' => 99,
			'negatives' => 0,
		);

		$params['number'] = array(
			'type' => 'integer',
			'upperbound' => 99,
			'negatives' => 0,
		);

		return $params;
	}

	public function valueProvider() {
		return array(
			'count' => array(
				array( 42, true, 42 ),
				array( '42', true, 42 ),
				array( 'foo', false ),
				array( 4.2, false ),
				array( array( 42 ), false ),
			),
			'amount' => array(
				array( 0, true, 0 ),
				array( '0', true, 0 ),
				array( 'foo', false, 42 ),
				array( 100, false, 42 ),
				array( -1, false, 42 ),
				array( 4.2, false, 42 ),
			),
			'number' => array(
				array( 42, true, 42 ),
				array( 'foo', false ),
				array( 100, false ),
				array( -1, false ),
				array( 4.2, false ),
			),
		);
	}

}
