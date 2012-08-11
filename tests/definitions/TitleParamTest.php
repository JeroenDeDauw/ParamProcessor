<?php

namespace Validator\Test;

/**
 * Unit test for the TitleParam class.
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
class TitleParamTest extends ParamDefinitionTest {

	/**
	 * @see ParamDefinitionTest::getDefinitions
	 */
	public function getDefinitions() {
		$params = parent::getDefinitions();

		$params['empty-empty'] = $params['empty'];
		$params['empty-empty']['hastoexist'] = false;

		$params['values-empty'] = $params['values'];
		$params['values-empty']['hastoexist'] = false;

		return $params;
	}

	/**
	 * @see ParamDefinitionTest::valueProvider
	 */
	public function valueProvider() {
		return array(
			'empty-empty' => array(
				array( 'foo bar page', true, \Title::newFromText( 'foo bar page' ) ),
				array( '|', false ),
				array( '', false ),
			),
			'empty' => array(
				array( 'foo bar page', false ),
				array( '|', false ),
				array( '', false ),
			),
			'values-empty' => array(
				array( 'foo', true, \Title::newFromText( 'foo' ) ),
				array( 'foo bar page', false ),
			),
			'values' => array(
				array( 'foo', false ),
				array( 'foo bar page', false ),
			),
		);
	}

	/**
	 * @see ParamDefinitionTest::getType
	 * @return string
	 */
	public function getType() {
		return 'title';
	}

}
