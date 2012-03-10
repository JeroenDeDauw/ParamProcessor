<?php

class TitleParam extends ParamDefinition {

	protected $hasToExist;

	public function setHasToExist( $hasToExist ) {
		$this->hasToExist = $hasToExist;
	}

	public function validate( Param $param, array /* of Param */ $params ) {
		$title = Title::newFromText( $param->getValue() );

		if( is_null( $title ) ) {
			return false;
		}

		return $this->hasToExist ? $title->isKnown() : true;
	}

	public function format( Param $param, array /* of Param */ $params ) {
		$value = Title::newFromText( trim( $value ) );
	}

}