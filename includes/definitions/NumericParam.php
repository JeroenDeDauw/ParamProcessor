<?php

/**
 * Base class for numeric parameter types.
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
abstract class NumericParam extends ParamDefinition {

	/**
	 * Lower bound of the range (included). Either a number or false, for no lower limit.
	 *
	 * @since 0.5
	 *
	 * @var false|int|float
	 */
	protected $lowerBound = false;

	/**
	 * Upper bound of the range (included). Either a number or false, for no upper limit.
	 *
	 * @since 0.5
	 *
	 * @var false|int|float
	 */
	protected $upperBound = false;

	/**
	 * Sets the lower bound (included).
	 *
	 * @since 0.5
	 *
	 * @param $lowerBound false|int|float
	 */
	public function setLowerBound( $lowerBound ) {
		$this->lowerBound = $lowerBound;
	}

	/**
	 * Sets the upper bound (included).
	 *
	 * @since 0.5
	 *
	 * @param $upperBound false|int|float
	 */
	public function setUpperBound( $upperBound ) {
		$this->upperBound = $upperBound;
	}

	/**
	 * Requires the value to be in the specified range.
	 *
	 * @since 0.5
	 *
	 * @param $lowerBound false|int|float
	 * @param $upperBound false|int|float
	 */
	public function setRange( $lowerBound, $upperBound ) {
		$this->lowerBound = $lowerBound;
		$this->upperBound = $upperBound;
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

		return ( $this->upperBound === false || $value <= $this->upperBound )
			&& ( $this->lowerBound === false || $value >= $this->lowerBound );
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

		if ( array_key_exists( 'range', $param ) ) {
			if ( is_array( $param['range'] ) && count( $param['range'] ) == 2 ) {
				$this->setRange( $param['range'][0], $param['range'][1] );
			}
			else {
				throw new MWException( 'The range argument must be an array with two elements' );
			}
		}

		if ( array_key_exists( 'lowerbound', $param ) ) {
			$this->setLowerBound( $param['lowerbound'] );
		}

		if ( array_key_exists( 'upperbound', $param ) ) {
			$this->setLowerBound( $param['upperbound'] );
		}
	}

}