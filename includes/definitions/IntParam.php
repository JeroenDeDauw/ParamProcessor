<?php

class IntParam extends ParamDefinition {

	protected $allowNegatives = true;

	public function setAllowNegatives( $allowNegatives ) {
		$this->allowNegatives = $allowNegatives;
	}

	public function validate( Param $param, array /* of Param */ $params ) {
		$value = $param->getValue();

		if ( $this->negativesAllowed && strpos( $value, '-' ) === 0 ) {
			$value = substr( $value, 1 );
		}

		return ctype_digit( (string)$value );
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$param->setValue( (int)$param->getValue() );
	}

}