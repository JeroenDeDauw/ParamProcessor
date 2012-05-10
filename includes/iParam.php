<?php

/**
 * Interface for objects representing an "instance" of a parameter.
 *
 * @since 0.5
 *
 * @file
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface iParam {

	function __construct( iParamDefinition $definition );

	function setUserValue( $paramName, $paramValue );

	function setValue( $value );

	function validate( array /* of ParamDefinition */ $definitions, array /* of Param */ $params );

	function format( array &$definitions, array $params );

	function getOriginalName();

	function getOriginalValue();

	function getErrors();

	function wasSetToDefault();

	function getDefinition();

	function &getValue();

	function getName();

}