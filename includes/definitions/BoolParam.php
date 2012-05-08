<?php

class BoolParam extends ParamDefinition {

	protected $true = array( 'yes', 'on', '1' );
	protected $false = array( 'no', 'off', '0' );

	/**
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'boolean';
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
		return in_array( $param->getValue(), $this->true )
			|| in_array( $param->getValue(), $this->false );
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
		$val = $param->getValue();

		if ( !is_bool( $val ) ) {
			$param->setValue( in_array( $val, $this->true ) );
		}
	}

}