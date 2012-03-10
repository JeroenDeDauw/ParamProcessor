<?php

class CharParam extends ParamDefinition {

	public function validate( Param $param, array /* of Param */ $params ) {
		return strlen( $param->getValue() ) === 1;
	}

}