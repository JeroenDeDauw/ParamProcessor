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
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param mixed $value
	 * @param Param $param
	 * @param array $definitions
	 * @param array $params
	 *
	 * @return boolean
	 */
	protected function validateValue( $value, Param $param, array $definitions, array $params ) {
		return strlen( $param->getValue() ) === 1;
	}

}