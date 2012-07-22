<?php

/**
 * Defines the boolean integer type.
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
class IntParam extends NumericParam {

	/**
	 * If negative values should be allowed or not.
	 *
	 * @since 0.5
	 *
	 * @param boolean $allowNegatives
	 */
	protected $allowNegatives = true;

	/**
	 * Sets if negative values should be allowed or not.
	 *
	 * @since 0.5
	 *
	 * @param boolean $allowNegatives
	 */
	public function setAllowNegatives( $allowNegatives ) {
		$this->allowNegatives = $allowNegatives;
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param $value mixed
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 *
	 * @return boolean
	 */
	protected function validateValue( $value, IParam $param, array $definitions, array $params ) {
		if ( !parent::validateValue( $value, $param, $definitions, $params ) ) {
			return false;
		}

		if ( !is_string( $value ) && !is_int( $value ) ) {
			return false;
		}

		if ( $this->allowNegatives && strpos( $value, '-' ) === 0 ) {
			$value = substr( $value, 1 );
		}

		return ctype_digit( (string)$value );
	}

	/**
	 * Formats the parameter value to it's final result.
	 *
	 * @since 0.5
	 *
	 * @param $value mixed
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 *
	 * @return mixed
	 */
	protected function formatValue( $value, IParam $param, array &$definitions, array $params ) {
		return (int)$value;
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

		if ( array_key_exists( 'negatives', $param ) ) {
			$this->setAllowNegatives( $param['negatives'] );
		}
	}

	/**
	 * Returns if negatives are allowed.
	 * Can be set via @see IntParam::setAllowNegatives
	 *
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function allowsNegatives() {
		return $this->allowNegatives;
	}

}