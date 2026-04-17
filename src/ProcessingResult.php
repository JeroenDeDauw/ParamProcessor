<?php

namespace ParamProcessor;

/**
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ProcessingResult {

	private $parameters;
	private $errors;

	public function __construct( array $parameters, array $errors = [] ) {
		$this->parameters = $parameters;
		$this->errors = $errors;
	}

	public function getParameters(): array {
		return $this->parameters;
	}

	/**
	 * @since 1.8
	 */
	public function getParameterArray(): array {
		$parameters = [];

		foreach ( $this->parameters as $parameter ) {
			$parameters[$parameter->getName()] = $parameter->getValue();
		}

		return $parameters;
	}

	public function getErrors(): array {
		return $this->errors;
	}

	public function hasFatal(): bool {
		foreach ( $this->errors as $error ) {
			if ( $error->isFatal() ) {
				return true;
			}
		}

		return false;
	}

}
