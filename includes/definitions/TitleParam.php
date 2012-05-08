<?php

class TitleParam extends ParamDefinition {

	/**
	 * @var boolean
	 */
	protected $hasToExist = true;

	/**
	 * @var Title|null
	 */
	protected $title;

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
		$this->title = Title::newFromText( $param->getValue() );

		if( is_null( $this->title ) ) {
			return false;
		}

		return $this->hasToExist ? $this->title->isKnown() : true;
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
		$param->setValue( $this->title );
	}

}