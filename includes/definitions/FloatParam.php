<?php

class FloatParam extends ParamDefinition {

	public function validate( Param $param, array /* of Param */ $params ) {
		return is_float( $param->getValue() )
			|| preg_match( '/^(-)?\d+((\.|,)\d+)?$/', $param->getValue() );
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$param->setValue( (float)$param->getValue() );
	}

}