<?php

/**
 * Indicate class aliases in a way PHPStorm and Eclipse understand.
 * This is purely an IDE helper file, and is not loaded by the extension.
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

namespace {

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class ParamDefinitionFactory extends ParamProcessor\ParamDefinitionFactory {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class ParamDefinition extends ParamProcessor\ParamDefinition {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class StringParam extends ParamProcessor\Definition\StringParam {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	interface IParamDefinition extends ParamProcessor\IParamDefinition {}

	/**
	 * @deprecated since 1.0, removal in 1.5
	 */
	class DimensionParam extends ParamProcessor\Definition\DimensionParam {}

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	class ProcessingError extends ParamProcessor\ProcessingError {}

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	class ValidatorOptions extends ParamProcessor\Options {}

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	interface IParam extends ParamProcessor\IParam {}

	}

namespace ParamProcessor {

	/**
	 * @deprecated since 1.0, removal in 1.2
	 */
	class StringParam extends \ParamProcessor\Definition\StringParam {}

}