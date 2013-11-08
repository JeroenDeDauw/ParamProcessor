<?php

/**
 * Indicate class aliases in a way PHPStorm and Eclipse understand.
 * This is purely an IDE helper file, and is not loaded by the extension.
 *
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

namespace {

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class ParamDefinitionFactory extends ParamProcessor\ParamDefinitionFactory {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class ParamDefinition extends ParamProcessor\ParamDefinition {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class StringParam extends ParamProcessor\Definition\StringParam {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	interface IParamDefinition extends ParamProcessor\IParamDefinition {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class DimensionParam extends ParamProcessor\Definition\DimensionParam {}

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	class ProcessingError extends ParamProcessor\ProcessingError {}

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	class ValidatorOptions extends ParamProcessor\Options {}

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	interface IParam extends ParamProcessor\IParam {}

	}

namespace ParamProcessor {

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	class StringParam extends \ParamProcessor\Definition\StringParam {}

}