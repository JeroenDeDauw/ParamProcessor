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
		$value = $param->getValue();

		if ( $this->negativesAllowed && strpos( $value, '-' ) === 0 ) {
			$value = substr( $value, 1 );
		}

		return ctype_digit( (string)$value );
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
		$param->setValue( (int)$param->getValue() );
	}

}