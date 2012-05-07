<?php

class IntParam extends ParamDefinition {

	protected $allowNegatives = true;

	/**
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'integer';
	}

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