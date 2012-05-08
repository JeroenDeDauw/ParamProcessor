<?php

/**
 * Defines the boolean parameter type.
 * Specifies the type specific validation and formatting logic.
 *
 * @since 0.5
 *
 * @file
 * @ingroup Validator
 * @ingroup ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
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
	 * @param $value mixed
	 * @param $param Param
	 * @param $definitions array of ParamDefinition
	 * @param $params array of Param
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
	 * @param $value mixed
	 * @param $param Param
	 * @param $definitions array of ParamDefinition
	 * @param $params array of Param
	 *
	 * @return mixed
	 */
	protected function formatValue( $value, Param $param, array $definitions, array $params ) {
		return is_bool( $value ) ? $value : in_array( $value, $this->true );
	}

}