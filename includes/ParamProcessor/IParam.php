<?php

namespace ParamProcessor;

/**
 * Interface for objects representing an "instance" of a parameter.
 *
 * NOTE: as of version 1.0, this class is for internal use only!
 *
 * @since 1.0
 *
 * @file
 * @ingroup ParamProcessor
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface IParam {

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param IParamDefinition $definition
	 */
	public function __construct( IParamDefinition $definition );

	/**
	 * Sets and cleans the original value and name.
	 *
	 * @since 1.0
	 *
	 * @param string $paramName
	 * @param string $paramValue
	 * @param Options $options
	 */
	public function setUserValue( $paramName, $paramValue, Options $options );

	/**
	 * Sets the value.
	 *
	 * @since 1.0
	 *
	 * @param mixed $value
	 */
	public function setValue( $value );

	/**
	 * Processes the parameter. This includes parsing, validation and additional formatting.
	 *
	 * @since 1.0
	 *
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 * @param Options $options
	 */
	public function process( array &$definitions, array $params, Options $options );

	/**
	 * Returns the original use-provided name.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getOriginalName();

	/**
	 * Returns the original use-provided value.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getOriginalValue();

	/**
	 * Returns all validation errors that occurred so far.
	 *
	 * @since 1.0
	 *
	 * @return array of ProcessingError
	 */
	public function getErrors();

	/**
	 * Gets if the parameter was set to it's default.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function wasSetToDefault();

	/**
	 * Returns the IParamDefinition this IParam was constructed from.
	 *
	 * @since 1.0
	 *
	 * @return IParamDefinition
	 */
	public function getDefinition();

	/**
	 * Returns the parameters value.
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public function &getValue();

	/**
	 * Returns if the name of the parameter.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function getName();

}
