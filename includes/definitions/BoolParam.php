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
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param mixed $value
	 * @param Param $param
	 * @param array $definitions
	 * @param array $params
	 *
	 * @return boolean
	 */
	protected function validateValue( $value, Param $param, array $definitions, array $params ) {
		return in_array( $value, $this->true )
			|| in_array( $value, $this->false );
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param mixed $value
	 * @param Param $param
	 * @param array $definitions
	 * @param array $params
	 *
	 * @return mixed
	 */
	protected function formatValue( $value, Param $param, array $definitions, array $params ) {
		return is_bool( $value ) ? $value : in_array( $value, $this->true );
	}

}