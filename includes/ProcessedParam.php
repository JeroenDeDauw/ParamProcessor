<?php

namespace ParamProcessor;

class ProcessedParam {

	/**
	 * @var mixed
	 */
	protected $value;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var bool|null
	 */
	protected $wasSetToDefault;

	/**
	 * @var mixed
	 */
	protected $originalValue;

	/**
	 * @var null|string
	 */
	protected $originalName;

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param boolean|null $wasSetToDefault
	 * @param mixed $originalValue
	 * @param string|null $originalName
	 */
	public function __construct( $name, $value, $wasSetToDefault = null, $originalName = null, $originalValue = null ) {
		$this->name = $name;
		$this->value = $value;
		$this->wasSetToDefault = $wasSetToDefault;
		$this->originalValue = $originalValue;
		$this->originalName = $originalName;
	}

	/**
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return bool|null
	 */
	public function wasSetToDefault() {
		return $this->wasSetToDefault;
	}

	/**
	 * @return mixed
	 */
	public function getOriginalValue() {
		return $this->originalValue;
	}

	/**
	 * @return null|string
	 */
	public function getOriginalName() {
		return $this->originalName;
	}

}
