<?php

/**
 * Object for holding options affecting the behaviour of a Validator object.
 *
 * @since 0.5
 *
 * @file
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorOptions {

	protected $name;

	protected $unknownInvalid = true;

	protected $lowercaseNames = true;

	protected $rawStringInputs = true;

	protected $trimValues = false;

	protected $lowercaseValues = false;

	/**
	 * Constructor.
	 *
	 * @since 0.5
	 */
	public function __construct() {
		
	}

	/**
	 * @since 0.5
	 *
	 * @param string $name
	 */
	public function setName( $name ) {
		$this->name = $name;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $unknownInvalid
	 */
	public function setUnknownInvalid( $unknownInvalid ) {
		$this->unknownInvalid = $unknownInvalid;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $lowercase
	 */
	public function setLowercaseNames( $lowercase ) {
		$this->lowercaseNames = $lowercase;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $rawInputs
	 */
	public function setRawStringInputs( $rawInputs ) {
		$this->rawStringInputs = $rawInputs;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $trim
	 */
	public function setTrimValues( $trim ) {
		$this->trimValues = $trim;
	}

	/**
	 * @since 0.5
	 *
	 * @param boolean $lowercase
	 */
	public function setLowercaseValues( $lowercase ) {
		$this->lowercaseValues = $lowercase;
	}

	/**
	 * @since 0.5
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function getUnknownInvalid() {
		return $this->unknownInvalid;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function getLowercaseNames() {
		return $this->lowercaseNames;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function getRawStringInputs() {
		return $this->rawStringInputs;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function getTrimValues() {
		return $this->trimValues;
	}

	/**
	 * @since 0.5
	 *
	 * @return boolean
	 */
	public function getLowercaseValues() {
		return $this->lowercaseValues;
	}

}
