<?php

class StringParam extends ParamDefinition {

	public function format( Param $param, array /* of Param */ $params ) {
		$param->setValue( (string)$param->getValue() );
	}

}