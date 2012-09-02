<?php

/**
 * Defines the boolean parameter type.
 * Specifies the type specific validation and formatting logic.
 *
 * @since 1.0
 *
 * @file
 * @ingroup Validator
 * @ingroup ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class BoolParam extends ParamDefinition {

	/**
	 * @see ParamDefinition::getStringValueParser
	 *
	 * @since 1.0
	 *
	 * @return StringValueParser
	 */
	protected function getStringValueParser() {
		return new BoolParser();
	}

}
