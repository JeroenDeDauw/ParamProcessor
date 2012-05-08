<?php

/**
 * Defines the string parameter type.
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
		$value = (string)$value;

		if ( $this->toLower ) {
			$value = strtolower( $value );
		}

		return $value;
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