<?php

class CharParam extends ParamDefinition {

	/**
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'char';
	}

	public function validate( Param $param, array /* of Param */ $params ) {
		return strlen( $param->getValue() ) === 1;
	}

}