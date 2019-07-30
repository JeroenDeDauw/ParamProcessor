<?php

namespace ParamProcessor;

use ParamProcessor\PackagePrivate\Param;

/**
 * Class for parameter validation of a single parser hook or other parametrized construct.
 *
 * @since 0.1
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Werner
 */
class Processor {

	/**
	 * Flag for unnamed default parameters used in Processor::setFunctionParams() to determine that
	 * a parameter should not have a named fallback.
	 *
	 * @since 0.4.13
	 */
	const PARAM_UNNAMED = 1;

	/**
	 * @var Param[]
	 */
	private $params;

	/**
	 * Associative array containing parameter names (keys) and their user-provided data (values).
	 * This list is needed because additional parameter definitions can be added to the $parameters
	 * field during validation, so we can't determine in advance if a parameter is unknown.
	 * @var string[]
	 */
	private $rawParameters = [];

	/**
	 * Array containing the names of the parameters to handle, ordered by priority.
	 * @var string[]
	 */
	private $paramsToHandle = [];

	/**
	 * @var ParamDefinition[]
	 */
	private $paramDefinitions = [];

	/**
	 * @var ProcessingError[]
	 */
	private $errors = [];

	private $options;

	public function __construct( Options $options ) {
		$this->options = $options;
	}

	/**
	 * Constructs and returns a Validator object based on the default options.
	 */
	public static function newDefault(): self {
		return new Processor( new Options() );
	}

	/**
	 * Constructs and returns a Validator object based on the provided options.
	 */
	public static function newFromOptions( Options $options ): self {
		return new Processor( $options );
	}

	/**
	 * Returns the options used by this Validator object.
	 */
	public function getOptions(): Options {
		return $this->options;
	}

	/**
	 * Determines the names and values of all parameters. Also takes care of default parameters.
	 * After that the resulting parameter list is passed to Processor::setParameters
	 *
	 * @since 0.4
	 *
	 * @param string[] $rawParams
	 * @param ParamDefinition[]|array[] $parameterDefinitions DEPRECATED! Use @see setParameterDefinitions instead
	 * @param array $defaultParams array of strings or array of arrays to define which parameters can be used unnamed.
	 *        The second value in array-form is reserved for flags. Currently, Processor::PARAM_UNNAMED determines that
	 *        the parameter has no name which can be used to set it. Therefore all these parameters must be set before
	 *        any named parameter. The effect is, that '=' within the string won't confuse the parameter anymore like
	 *        it would happen with default parameters that still have a name as well.
	 */
	public function setFunctionParams( array $rawParams, array $parameterDefinitions = [], array $defaultParams = [] ) {
		$lastUnnamedDefaultNr = -1;

		/*
		 * Find last parameter with self::PARAM_UNNAMED set. Tread all parameters in front as
		 * the flag were set for them as well to ensure that there can't be any unnamed params
		 * after the first named param. Wouldn't be possible to determine which unnamed value
		 * belongs to which parameter otherwise.
		 */
		for( $i = count( $defaultParams ) - 1; $i >= 0; $i-- ) {
			$dflt = $defaultParams[$i];
			if( is_array( $dflt ) && !empty( $dflt[1] ) && ( $dflt[1] | self::PARAM_UNNAMED ) ) {
				$lastUnnamedDefaultNr = $i;
				break;
			}
		}

		$parameters = [];
		$nr = 0;
		$defaultNr = 0;

		foreach ( $rawParams as $arg ) {
			// Only take into account strings. If the value is not a string,
			// it is not a raw parameter, and can not be parsed correctly in all cases.
			if ( is_string( $arg ) ) {
				$parts = explode( '=', $arg, ( $nr <= $lastUnnamedDefaultNr ? 1 : 2 ) );

				// If there is only one part, no parameter name is provided, so try default parameter assignment.
				// Default parameters having self::PARAM_UNNAMED set for having no name alias go here in any case.
				if ( count( $parts ) == 1 ) {
					// Default parameter assignment is only possible when there are default parameters!
					if ( count( $defaultParams ) > 0 ) {
						$defaultParam = array_shift( $defaultParams );
						if( is_array( $defaultParam ) ) {
							$defaultParam = $defaultParam[0];
						}
						$defaultParam = strtolower( $defaultParam );

						$parameters[$defaultParam] = [
							'original-value' => trim( $parts[0] ),
							'default' => $defaultNr,
							'position' => $nr
						];
						$defaultNr++;
					}
				} else {
					$paramName = trim( strtolower( $parts[0] ) );

					$parameters[$paramName] = [
						'original-value' => trim( $parts[1] ),
						'default' => false,
						'position' => $nr
					];

					// Let's not be evil, and remove the used parameter name from the default parameter list.
					// This code is basically a remove array element by value algorithm.
					$newDefaults = [];

					foreach( $defaultParams as $defaultParam ) {
						if ( $defaultParam != $paramName ) {
							$newDefaults[] = $defaultParam;
						}
					}

					$defaultParams = $newDefaults;
				}
			}

			$nr++;
		}

		$this->setParameters( $parameters, $parameterDefinitions );
	}

	/**
	 * @since 1.6.0
	 * @param ParamDefinition[] $paramDefinitions
	 */
	public function setParameterDefinitions( array $paramDefinitions ) {
		$this->paramDefinitions = $paramDefinitions;
	}

	/**
	 * Loops through a list of provided parameters, resolves aliasing and stores errors
	 * for unknown parameters and optionally for parameter overriding.
	 *
	 * @param array $parameters Parameter name as key, parameter value as value
	 * @param ParamDefinition[]|array[] $paramDefinitions DEPRECATED! Use @see setParameterDefinitions instead
	 */
	public function setParameters( array $parameters, array $paramDefinitions = [] ) {
		$this->paramDefinitions = ParamDefinition::getCleanDefinitions( $paramDefinitions );

		// Loop through all the user provided parameters, and distinguish between those that are allowed and those that are not.
		foreach ( $parameters as $paramName => $paramData ) {
			if ( $this->options->lowercaseNames() ) {
				$paramName = strtolower( $paramName );
			}

			if ( $this->options->trimNames() ) {
				$paramName = trim( $paramName );
			}

			$paramValue = is_array( $paramData ) ? $paramData['original-value'] : $paramData;

			$this->rawParameters[$paramName] = $paramValue;
		}
	}

	/**
	 * @param string $message
	 * @param string[] $tags
	 * @param integer $severity
	 */
	private function registerNewError( string $message, array $tags = [], int $severity = ProcessingError::SEVERITY_NORMAL ) {
		$this->registerError(
			new ProcessingError(
				$message,
				$severity,
				$this->options->getName(),
				(array)$tags
			)
		);
	}

	private function registerError( ProcessingError $error ) {
		$error->element = $this->options->getName();
		$this->errors[] = $error;
		ProcessingErrorHandler::addError( $error );
	}

	/**
	 * Validates and formats all the parameters (but aborts when a fatal error occurs).
	 *
	 * @since 0.4
	 * @deprecated since 1.0, use processParameters
	 */
	public function validateParameters() {
		$this->doParamProcessing();
	}

	public function processParameters(): ProcessingResult {
		$this->doParamProcessing();

		if ( !$this->hasFatalError() && $this->options->unknownIsInvalid() ) {
			// Loop over the remaining raw parameters.
			// These are unrecognized parameters, as they where not used by any parameter definition.
			foreach ( $this->rawParameters as $paramName => $paramValue ) {
				$this->registerNewError(
					$paramName . ' is not a valid parameter', // TODO
					[ $paramName ]
				);
			}
		}

		return $this->newProcessingResult();
	}

	private function newProcessingResult(): ProcessingResult {
		$parameters = [];

		if ( !is_array( $this->params ) ) {
			$this->params = [];
		}

		foreach ( $this->params as $parameter ) {
			// TODO
			$processedParam = new ProcessedParam(
				$parameter->getName(),
				$parameter->getValue(),
				$parameter->wasSetToDefault()
			);

			// TODO: it is possible these values where set even when the value defaulted,
			// so this logic is not correct and could be improved
			if ( !$parameter->wasSetToDefault() ) {
				$processedParam->setOriginalName( $parameter->getOriginalName() );
				$processedParam->setOriginalValue( $parameter->getOriginalValue() );
			}

			$parameters[$processedParam->getName()] = $processedParam;
		}

		return new ProcessingResult(
			$parameters,
			$this->getErrors()
		);
	}

	private function doParamProcessing() {
		$this->errors = [];

		$this->getParamsToProcess( [], $this->paramDefinitions );

		while ( $this->paramsToHandle !== [] && !$this->hasFatalError() ) {
			$this->processOneParam();
		}
	}

	private function processOneParam() {
		$paramName = array_shift( $this->paramsToHandle );
		$definition = $this->paramDefinitions[$paramName];

		$param = new Param( $definition );

		$setUserValue = $this->attemptToSetUserValue( $param );

		// If the parameter is required but not provided, register a fatal error and stop processing.
		if ( !$setUserValue && $param->isRequired() ) {
			$this->registerNewError(
				"Required parameter '$paramName' is missing", // FIXME: i18n validator_error_required_missing
				[ $paramName, 'missing' ],
				ProcessingError::SEVERITY_FATAL
			);
			return;
		}

		$this->params[$param->getName()] = $param;

		$initialSet = $this->paramDefinitions;

		$param->process( $this->paramDefinitions, $this->params, $this->options );

		foreach ( $param->getErrors() as $error ) {
			$this->registerError( $error );
		}

		if ( $param->hasFatalError() ) {
			return;
		}

		$this->getParamsToProcess( $initialSet, $this->paramDefinitions );
	}

	/**
	 * Gets an ordered list of parameters to process.
	 * @throws \UnexpectedValueException
	 */
	private function getParamsToProcess( array $initialParamSet, array $resultingParamSet ) {
		if ( $initialParamSet === [] ) {
			$this->paramsToHandle = array_keys( $resultingParamSet );
		}
		else {
			if ( !is_array( $this->paramsToHandle ) ) {
				$this->paramsToHandle = [];
			}

			foreach ( $resultingParamSet as $paramName => $parameter ) {
				if ( !array_key_exists( $paramName, $initialParamSet ) ) {
					$this->paramsToHandle[] = $paramName;
				}
			}
		}

		$this->paramsToHandle = $this->getParameterNamesInEvaluationOrder( $this->paramDefinitions, $this->paramsToHandle );
	}

	/**
	 * @param ParamDefinition[] $paramDefinitions
	 * @param string[] $paramsToHandle
	 *
	 * @return array
	 */
	private function getParameterNamesInEvaluationOrder( array $paramDefinitions, array $paramsToHandle ): array {
		$dependencyList = [];

		foreach ( $paramsToHandle as $paramName ) {
			$dependencies = [];

			if ( !array_key_exists( $paramName, $paramDefinitions ) ) {
				throw new \UnexpectedValueException( 'Unexpected parameter name "' . $paramName . '"' );
			}

			if ( !is_object( $paramDefinitions[$paramName] ) || !( $paramDefinitions[$paramName] instanceof ParamDefinition ) ) {
				throw new \UnexpectedValueException( 'Parameter "' . $paramName . '" is not a ParamDefinition' );
			}

			// Only include dependencies that are in the list of parameters to handle.
			foreach ( $paramDefinitions[$paramName]->getDependencies() as $dependency ) {
				if ( in_array( $dependency, $paramsToHandle ) ) {
					$dependencies[] = $dependency;
				}
			}

			$dependencyList[$paramName] = $dependencies;
		}

		$sorter = new TopologicalSort( $dependencyList, true );

		return $sorter->doSort();
	}

	/**
	 * Tries to find a matching user provided value and, when found, assigns it
	 * to the parameter, and removes it from the raw values. Returns a boolean
	 * indicating if there was any user value set or not.
	 */
	private function attemptToSetUserValue( Param $param ): bool {
		if ( array_key_exists( $param->getName(), $this->rawParameters ) ) {
			$param->setUserValue( $param->getName(), $this->rawParameters[$param->getName()], $this->options );
			unset( $this->rawParameters[$param->getName()] );
			return true;
		}
		else {
			foreach ( $param->getDefinition()->getAliases() as $alias ) {
				if ( array_key_exists( $alias, $this->rawParameters ) ) {
					$param->setUserValue( $alias, $this->rawParameters[$alias], $this->options );
					unset( $this->rawParameters[$alias] );
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @deprecated since 1.0
	 * @return Param[]
	 */
	public function getParameters(): array {
		return $this->params;
	}

	/**
	 * @deprecated since 1.0
	 */
	public function getParameter( string $parameterName ): Param {
		return $this->params[$parameterName];
	}

	/**
	 * Returns an associative array with the parameter names as key and their
	 * corresponding values as value.
	 * @deprecated since 1.7 - use processParameters() return value
	 */
	public function getParameterValues(): array {
		$parameters = [];

		foreach ( $this->params as $parameter ) {
			$parameters[$parameter->getName()] = $parameter->getValue();
		}

		return $parameters;
	}

	/**
	 * @deprecated since 1.7 - use processParameters() return value
	 * @return ProcessingError[]
	 */
	public function getErrors(): array {
		return $this->errors;
	}

	/**
	 * @deprecated since 1.7 - use processParameters() return value
	 * @return string[]
	 */
	public function getErrorMessages(): array {
		$errors = [];

		foreach ( $this->errors as $error ) {
			$errors[] = $error->getMessage();
		}

		return $errors;
	}

	/**
	 * @deprecated since 1.7 - use processParameters() return value
	 */
	public function hasErrors(): bool {
		return !empty( $this->errors );
	}

	/**
	 * @deprecated since 1.7 - use processParameters() return value
	 * @return ProcessingError|boolean false
	 */
	public function hasFatalError() {
		foreach ( $this->errors as $error ) {
			if ( $error->isFatal() ) {
				return $error;
			}
		}

		return false;
	}

}
