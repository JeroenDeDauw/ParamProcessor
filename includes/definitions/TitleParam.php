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

	public function validate( Param $param, array /* of Param */ $params ) {
		$this->title = Title::newFromText( $param->getValue() );

		if( is_null( $this->title ) ) {
			return false;
		}

		return $this->hasToExist ? $this->title->isKnown() : true;
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$param->setValue( $this->title );
	}

}