<?php

namespace ParamProcessor;

/**
 * Object representing a parameter that has been processed.
 *
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ProcessedParam {

	private $value;
	private $name;
	private $wasSetToDefault;
	private $originalValue = null;
	private $originalName = null;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $wasSetToDefault
	 * @param string|null $originalName
	 * @param mixed $originalValue
	 */
	public function __construct( string $name, $value, bool $wasSetToDefault, string $originalName = null, $originalValue = null ) {
		$this->name = $name;
		$this->value = $value;
		$this->wasSetToDefault = $wasSetToDefault;
		$this->originalName = $originalName;
		$this->originalValue = $originalValue;
	}

	public function setOriginalName( string $originalName ) {
		$this->originalName = $originalName;
	}

	/**
	 * @param mixed $originalValue
	 */
	public function setOriginalValue( $originalValue ) {
		$this->originalValue = $originalValue;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	public function getName(): string {
		return $this->name;
	}

	public function wasSetToDefault(): bool {
		return $this->wasSetToDefault;
	}

	/**
	 * @return null|mixed
	 */
	public function getOriginalValue() {
		return $this->originalValue;
	}

	public function getOriginalName(): ?string {
		return $this->originalName;
	}

}
