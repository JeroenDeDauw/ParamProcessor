<?php

/**
 * Defines the title parameter type.
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
class TitleParam extends ParamDefinition {

	/**
	 * @var boolean
	 */
	protected $hasToExist = true;

	/**
	 * @var array of Title|null
	 */
	protected $title = array();

	/**
	 * @since 0.5
	 * @param boolean $hasToExist
	 */
	public function setHasToExist( $hasToExist ) {
		$this->hasToExist = $hasToExist;
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

		$title = Title::newFromText( $value );

		if( is_null( $title ) ) {
			return false;
		}

		$this->titles[$value] = $title;

		return $this->hasToExist ? $title->isKnown() : true;
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
		return $this->title[$value];
	}

}