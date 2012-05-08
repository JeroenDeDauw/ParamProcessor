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
		return is_float( $param->getValue() )
			|| preg_match( '/^(-)?\d+((\.|,)\d+)?$/', $param->getValue() );
	}

	/**
	 * Formats the parameters value to it's final form.
	 *
	 * @since 0.5
	 *
	 * @param Param $param
	 * @param $definitions array of ParamDefinition
	 * @param $params array of Param
	 */
	public function format( Param $param, array $definitions, array $params ) {
		$param->setValue( (float)$param->getValue() );
	}

}