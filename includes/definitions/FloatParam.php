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

	public function validate( Param $param, array /* of Param */ $params ) {
		return is_float( $param->getValue() )
			|| preg_match( '/^(-)?\d+((\.|,)\d+)?$/', $param->getValue() );
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$param->setValue( (float)$param->getValue() );
	}

}