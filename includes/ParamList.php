<?php

class ParamList implements Iterator {

	/**
	 * @var integer
	 */
	protected  $key;

	/**
	 * @var Param
	 */
	protected $current;

	protected $rawValues;

	protected $definitions;

	/**
	 * @param array $rawValues
	 * @param array $definitions
	 */
	public function __construct( array $rawValues, array $definitions ) {
		$this->definitions = $definitions;
		$this->rawValues = $rawValues;
		$this->key = 0;
		$this->setCurrent();
	}

	/**
	 * @param $row
	 */
	protected function setCurrent() {
		$value = current( $this->rawValues );

		if ( $value === false ) {
			$this->current = false;
		} else {
			$rawName = key( $this->rawValues );
			$cleanName = $rawName; // TODO
			$this->current = new Param( $this->definitions[$cleanName] );
			$this->current->setUserValue( $rawName, $value );
		}
	}

	/**
	 * @return integer
	 */
	public function count() {
		return count( $this->rawValues );
	}

	/**
	 * @return boolean
	 */
	public function isEmpty() {
		return empty( $this->rawValues );
	}

	/**
	 * @return Param
	 */
	public function current() {
		return $this->current;
	}

	/**
	 * @return integer
	 */
	public function key() {
		return $this->key;
	}

	public function next() {
		$row = next( $this->rawValues );
		$this->setCurrent( $row );
		$this->key++;
	}

	public function rewind() {
		rewind( $this->res );
		$this->key = 0;
		$this->setCurrent();
	}

	/**
	 * @return boolean
	 */
	public function valid() {
		return $this->current !== false;
	}

}