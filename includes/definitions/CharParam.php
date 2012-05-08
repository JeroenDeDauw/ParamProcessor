<?php

class CharParam extends StringParam {

	/**
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'char';
	}

	/**
	 * Validates the parameters value.
	 *
	 * @since 0.5
	 *
	 * @param Param $param
	 * @param $definitions array of ParamDefinition
	 * @param $params array of Param
	 *
	 * @return boolean
	 */
	public function validate( Param $param, array $definitions, array $params ) {
		return strlen( $param->getValue() ) === 1;
	}

}