<?php

namespace ParamProcessor;

/**
 * Object representing a parameter that has been processed.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.0
 *
 * @file
 * @ingroup ParamProcessor
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ProcessedParam {

	/**
	 * @since 1.0
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * @since 1.0
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * @since 1.0
	 *
	 * @var bool
	 */
	protected $wasSetToDefault;

	/**
	 * @since 1.0
	 *
	 * @var null|mixed
	 */
	protected $originalValue = null;

	/**
	 * @since 1.0
	 *
	 * @var null|string
	 */
	protected $originalName = null;

	/**
	 * @since 1.0
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param boolean $wasSetToDefault
	 * @param string|null $originalName
	 * @param mixed $originalValue
	 */
	public function __construct( $name, $value, $wasSetToDefault, $originalName = null, $originalValue = null ) {
		$this->name = $name;
		$this->value = $value;
		$this->wasSetToDefault = $wasSetToDefault;
		$this->originalName = $originalName;
		$this->originalValue = $originalValue;
	}

	/**
	 * @since 1.0
	 *
	 * @param string $originalName
	 */
	public function setOriginalName( $originalName ) {
		$this->originalName = $originalName;
	}

	/**
	 * @since 1.0
	 *
	 * @param mixed $originalValue
	 */
	public function setOriginalValue( $originalValue ) {
		$this->originalValue = $originalValue;
	}

	/**
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @since 1.0
	 *
	 * @return bool
	 */
	public function wasSetToDefault() {
		return $this->wasSetToDefault;
	}

	/**
	 * @since 1.0
	 *
	 * @return null|mixed
	 */
	public function getOriginalValue() {
		return $this->originalValue;
	}

	/**
	 * @since 1.0
	 *
	 * @return null|string
	 */
	public function getOriginalName() {
		return $this->originalName;
	}

}
