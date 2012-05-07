<?php

class StringParam extends ParamDefinition {

	/**
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'string';
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$param->setValue( (string)$param->getValue() );
	}

}