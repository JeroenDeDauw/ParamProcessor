<?php

namespace ParamProcessor;

/**
 * Object for holding options affecting the behavior of a ParamProcessor object.
 *
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Options {

	private $name;

	// During setup
	private $unknownInvalid = true;
	private $lowercaseNames = true;
	private $trimNames = true;
	private $acceptOverriding = true;

	// During clean
	private $trimValues = true;
	private $lowercaseValues = false;

	// During validation
	private $rawStringInputs = true;

	public function setName( string $name ) {
		$this->name = $name;
	}

	public function setUnknownInvalid( bool $unknownInvalid ) {
		$this->unknownInvalid = $unknownInvalid;
	}

	public function setLowercaseNames( bool $lowercase ) {
		$this->lowercaseNames = $lowercase;
	}

	/**
	 * @deprecated since 1.7
	 */
	public function setRawStringInputs( bool $rawInputs ) {
		$this->rawStringInputs = $rawInputs;
	}

	public function setTrimNames( bool $trim ) {
		$this->trimNames = $trim;
	}

	public function setTrimValues( bool $trim ) {
		$this->trimValues = $trim;
	}

	public function setLowercaseValues( bool $lowercase ) {
		$this->lowercaseValues = $lowercase;
	}

	public function getName(): string {
		return $this->name ?? '';
	}

	public function unknownIsInvalid(): bool {
		return $this->unknownInvalid;
	}

	public function lowercaseNames(): bool {
		return $this->lowercaseNames;
	}

	/**
	 * @deprecated since 1.7
	 */
	public function isStringlyTyped(): bool {
		return $this->rawStringInputs;
	}

	public function trimNames(): bool {
		return $this->trimNames;
	}

	public function trimValues(): bool {
		return $this->trimValues;
	}

	public function lowercaseValues(): bool {
		return $this->lowercaseValues;
	}

	public function acceptOverriding(): bool {
		return $this->acceptOverriding;
	}

}
