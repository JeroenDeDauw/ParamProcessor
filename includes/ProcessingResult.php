<?php

namespace ParamProcessor;

class ProcessingResult {

	/**
	 * @var ProcessedParam[]
	 */
	protected $parameters;

	/**
	 * @var ProcessingError[]
	 */
	protected $errors;

	/**
	 * @param ProcessedParam[] $parameters
	 * @param ProcessingError[] $errors
	 */
	public function __construct( array $parameters, array $errors ) {
		$this->parameters = $parameters;
		$this->errors = $errors;
	}

	/**
	 * @return ProcessedParam[]
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * @return ProcessingError[]
	 */
	public function getErrors() {
		return $this->errors;
	}

	public function hasFatal() {
		return false; // TODO
	}

}