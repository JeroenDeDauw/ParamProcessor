<?php

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
	 * Returns an identifier for the parameter type.
	 * @since 0.5
	 * @return string
	 */
	public function getType() {
		return 'title';
	}

	public function setHasToExist( $hasToExist ) {
		$this->hasToExist = $hasToExist;
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
	 * @param mixed $value
	 * @param Param $param
	 * @param array $definitions
	 * @param array $params
	 *
	 * @return mixed
	 */
	protected function formatValue( $value, Param $param, array $definitions, array $params ) {
		return $this->title[$value];
	}

}