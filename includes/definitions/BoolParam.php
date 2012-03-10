<?php

class BoolParam extends ParamDefinition {

	protected $true = array( 'yes', 'on', '1' );
	protected $false = array( 'no', 'off', '0' );

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