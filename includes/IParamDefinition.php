<?php

/**
 * Interface for parameter definition classes.
 *
 * @since 0.5
 *
 * @file
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface IParamDefinition {

	function addAliases( $aliases );

	function addCriteria( $criteria );

	function addDependencies( $dependencies );

	function addManipulations( $manipulations );

	function format( IParam $param, array &$definitions, array $params );

	function getAliases();

	function getCriteria();

	function getDefault();

	function getDelimiter();

	function getDependencies();

	function getManipulations();

	function getMessage();

	function getName();

	function getType();

	function hasAlias( $alias );

	function hasDependency( $dependency );

	function isList();

	function isRequired();

	function setDefault( $default, $manipulate = true );

	function setDelimiter( $delimiter );

	function setDoManipulationOfDefault( $doOrDoNotThereIsNoTry );

	function setMessage( $message );

	function shouldManipulateDefault();

	function validate( IParam $param, array $definitions, array $params );

	/**
	 * Returns a message key for a message describing the parameter type.
	 *
	 * @since 0.5
	 *
	 * @return string
	 */
	function getTypeMessage();

}