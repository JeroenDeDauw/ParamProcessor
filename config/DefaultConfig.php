<?php

/**
 * This file assigns the default values to all ParameterProcessor settings.
 *
 * This file is NOT an entry point the ParameterProcessor library. Use ParameterProcessor.php.
 * It should furthermore not be included from outside the library.
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
 * @ingroup ParameterProcessor
 *
 * @licence GNU GPL v2+
 */

if ( !defined( 'ParamProcessor_VERSION' ) ) {
	die( 'Not an entry point.' );
}

$egValidatorSettings = array();

$wgParamDefinitions = array(
	'boolean' => array(
		'string-parser' => '\ValueParsers\BoolParser',
		'validation-callback' => 'is_bool',
	),
	'float' => array(
		'string-parser' => '\ValueParsers\FloatParser',
		'validation-callback' => function( $value ) {
			return is_float( $value ) || is_int( $value );
		},
		'validator' => '\ValueValidators\RangeValidator',
	),
	'integer' => array(
		'string-parser' => '\ValueParsers\IntParser',
		'validation-callback' => 'is_int',
		'validator' => '\ValueValidators\RangeValidator',
	),
	'string' => array(
		'validator' => '\ValueValidators\StringValidator',
		'definition' => '\ParamProcessor\StringParam',
	),
	'title' => array(
		'string-parser' => '\ValueParsers\TitleParser',
		'validator' => '\ValueValidators\TitleValidator',
	),
	'dimension' => array(
		'definition' => '\ParamProcessor\DimensionParam',
		'validator' => '\ValueValidators\DimensionValidator',
	),
	'coordinate' => array(
		'string-parser' => '\ValueParsers\GeoCoordinateParser',
	),
);