<?php

class FloatParam extends ParamDefinition {

	/**
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'float';
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
		return is_float( $value )
			|| preg_match( '/^(-)?\d+((\.|,)\d+)?$/', $value );
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
	 * @return mixed
	 */
	protected function formatValue( $value, Param $param, array $definitions, array $params ) {
		return (float)$value;
	}

}