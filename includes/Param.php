<?php

/**
 * Parameter class, representing the "instance" of a parameter.
 * Holds a ParamDefinition, user provided input (name & value) and processing state.
 *
 * @since 0.5
 *
 * @file Param.php
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Param {

	/**
	 * The original parameter name as provided by the user. This can be the
	 * main name or an alias.
	 *
	 * @since 0.5
	 *
	 * @var string
	 */
	protected $originalName;

	/**
	 * The original value as provided by the user. This is mainly retained for
	 * usage in error messages when the parameter turns out to be invalid.
	 *
	 * @since 0.5
	 *
	 * @var string
	 */
	protected $originalValue;

	/**
	 * The value of the parameter.
	 *
	 * @since 0.5
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Keeps track of how many times the parameter has been set by the user.
	 * This is used to detect overrides and for figuring out a parameter is missing.
	 *
	 * @since 0.5
	 *
	 * @var integer
	 */
	protected $setCount = 0;

	/**
	 * List of validation errors for this parameter.
	 *
	 * @since 0.5
	 *
	 * @var array of ValidationError
	 */
	protected $errors = array();

	/**
	 * Indicates if the parameter was set to it's default.
	 *
	 * @since 0.5
	 *
	 * @var boolean
	 */
	protected $defaulted = false;

	/**
	 * The definition of the parameter.
	 *
	 * @since 0.5
	 *
	 * @var ParamDefinition
	 */
	protected $definition;

	public function __construct( ParamDefinition $definition ) {
		$this->definition = $definition;
	}

	/**
	 * Sets and cleans the original value and name.
	 *
	 * @since 0.5
	 *
	 * @param string $paramName
	 * @param string $paramValue
	 *
	 * @return boolean
	 */
	public function setUserValue( $paramName, $paramValue ) {
		if ( $this->setCount > 0 && !self::$acceptOverriding ) {
			// TODO
			return false;
		}
		else {
			$this->originalName = $paramName;
			$this->originalValue = $paramValue;

			$this->cleanValue();

			$this->setCount++;

			return true;
		}
	}

	/**
	 * Sets the value.
	 *
	 * @since 0.5
	 *
	 * @param mixed $value
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}

	/**
	 * Sets the $value to a cleaned value of $originalValue.
	 *
	 * @since 0.5
	 */
	protected function cleanValue() {
		$this->value = $this->originalValue;

		if ( $this->definition->trimBeforeValidate() ) {
			$this->value = trim( $this->value );
		}
	}

	/**
	 * Validates the parameter value and sets the value to it's default when errors occur.
	 *
	 * @since 0.5
	 *
	 * @param array $paramDefinitions
	 */
	public function validate( array /* of ParamDefinition */ $paramDefinitions ) {
		$this->doValidation( $paramDefinitions );
	}

	/**
	 * Applies the parameter manipulations.
	 *
	 * @since 0.5
	 *
	 * @param array $paramDefinitions
	 */
	public function format( array &$paramDefinitions ) {
		if ( $this->definition->shouldManipulateDefault() || !$this->wasSetToDefault() ) {
			$this->definition->format( $this, $paramDefinitions );

			$parameter = $this->toParameter();

			foreach ( $this->definition->getManipulations() as $manipulation ) {
				$manipulation->manipulate( $parameter, $paramDefinitions );
			}
		}
	}

	/**
	 * Compatibility helper method, will be removed in 0.7.
	 *
	 * @deprecated
	 * @since 0.5
	 *
	 * @return Parameter
	 */
	protected function toParameter() {
		$parameter = new Parameter(
			$this->definition->getName(),
			$this->definition->getType(),
			$this->definition->getDefault(),
			$this->definition->getAliases(),
			$this->definition->getCriteria(),
			$this->definition->getDependencies()
		);

		$parameter->addManipulations( $this->definition->getManipulations() );
		$parameter->setUserValue( $this->getName(), $this->getValue() );

		return $parameter;
	}

	/**
	 * Validates the parameter value.
	 * Also sets the value to the default when it's not set or invalid, assuming there is a default.
	 *
	 * @since 0.5
	 *
	 * @param array $paramDefinitions
	 */
	protected function doValidation( array  /* of ParamDefinition */ $paramDefinitions ) {
		if ( $this->setCount == 0 ) {
			if ( $this->definition->isRequired() ) {
				// This should not occur, so throw an exception.
				throw new Exception( 'Attempted to validate a required parameter without first setting a value.' );
			}
			else {
				$this->setToDefault();
			}
		}
		else {
			$validationResult = $this->definition->validate( $this, $paramDefinitions );

			if ( is_array( $validationResult ) ) {
				foreach ( $validationResult as /* ValidationError */ $error ) {
					$error->addTags( $this->getName() );
					$this->errors[] = $error;
				}
			}

			$this->validateCriteria( $paramDefinitions );
			$this->setToDefaultIfNeeded();
		}
	}

	/**
	 * Sets the parameter value to the default if needed.
	 *
	 * @since 0.5
	 */
	protected function setToDefaultIfNeeded() {
		if ( count( $this->errors ) > 0 && !$this->hasFatalError() ) {
			$this->setToDefault();
		}
	}

	/**
	 * Validates the provided value against all criteria.
	 *
	 * @deprecated removal in 0.7
	 * @since 0.5
	 *
	 * @param array $parameters
	 */
	protected function validateCriteria( array $parameters ) {
		$parameter = $this->toParameter();

		foreach ( $this->definition->getCriteria() as $criterion ) {
			$validationResult = $criterion->validate( $parameter, $parameters );

			if ( !$validationResult->isValid() ) {
				$this->handleValidationError( $validationResult );

				if ( !self::$accumulateParameterErrors || $this->hasFatalError() ) {
					break;
				}
			}
		}
	}

	/**
	 * Handles any validation errors that occurred for a single criterion.
	 *
	 * @deprecated removal in 0.7
	 * @since 0.5
	 *
	 * @param CriterionValidationResult $validationResult
	 */
	protected function handleValidationError( CriterionValidationResult $validationResult ) {
		foreach ( $validationResult->getErrors() as $error ) {
			$error->addTags( $this->getName() );
			$this->errors[] = $error;
		}
	}

	/**
	 * Returns the original use-provided name.
	 *
	 * @since 0.5
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getOriginalName() {
		if ( $this->setCount == 0 ) {
			throw new MWException( 'No user imput set to the parameter yet, so the original name does not exist' );
		}
		return $this->originalName;
	}

	/**
	 * Returns the original use-provided value.
	 *
	 * @since 0.5
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getOriginalValue() {
		if ( $this->setCount == 0 ) {
			throw new MWException( 'No user imput set to the parameter yet, so the original value does not exist' );
		}
		return $this->originalValue;
	}

	/**
	 * Returns all validation errors that occurred so far.
	 *
	 * @since 0.5
	 *
	 * @return array of ValidationError
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Sets the parameter value to the default.
	 *
	 * @since 0.5
	 */
	protected function setToDefault() {
		$this->defaulted = true;
		$this->value = $this->definition->getDefault();
	}

	/**
	 * Gets if the parameter was set to it's default.
	 *
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function wasSetToDefault() {
		return $this->defaulted;
	}

	/**
	 * Returns false when there are no fatal errors or an ValidationError when one is found.
	 *
	 * @return mixed false or ValidationError
	 */
	public function hasFatalError() {
		foreach ( $this->errors as $error ) {
			if ( $error->isFatal() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @return ParamDefinition
	 */
	public function getDefinition() {
		return $this->definition;
	}

	/**
	 * Returns the parameters value.
	 *
	 * @since 0.5
	 *
	 * @return mixed
	 */
	public function &getValue() {
		return $this->value;
	}

	/**
	 * Returns if the parameter is required or not.
	 *
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function isRequired() {
		return $this->definition->isRequired();
	}

	/**
	 * Returns if the name of the parameter.
	 *
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function getName() {
		return $this->definition->getName();
	}

	/**
	 * Returns the parameter name aliases.
	 *
	 * @since 0.5
	 *
	 * @return array
	 */
	public function getAliases() {
		return $this->definition->getAliases();
	}

}