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

	public function validate( Param $param, array /* of Param */ $params ) {
		return in_array( $param->getValue(), $this->true )
			|| in_array( $param->getValue(), $this->false );
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$val = $param->getValue();

		if ( !is_bool( $val ) ) {
			$param->setValue( in_array( $val, $this->true ) );
		}
	}

}