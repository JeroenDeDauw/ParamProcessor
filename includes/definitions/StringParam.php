<?php

class StringParam extends ParamDefinition {

	/**
	 * Indicates if the parameter should be lowercased post validation.
	 *
	 * @since 0.5
	 *
	 * @var boolean
	 */
	protected $toLower = false;

	/**
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'string';
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$value = (string)$param->getValue();

		if ( $this->toLower ) {
			$value = strtolower( $value );
		}

		$param->setValue( $value );
	}

	/**
	 * Sets the parameter definition values contained in the provided array.
	 *
	 * @since 0.5
	 *
	 * @param array $param
	 */
	public function setArrayValues( array $param ) {
		parent::setArrayValues( $param );

		if ( array_key_exists( 'tolower', $param ) ) {
			$this->toLower = $param['tolower'];
		}
	}

}