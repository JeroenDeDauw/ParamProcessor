<?php

/**
 * This file assigns the default values to all ParameterProcessor settings.
 *
 * @licence GNU GPL v2+
 */

$GLOBALS['egValidatorSettings'] = [
	'errorListMinSeverity' => 'minor',
];

$GLOBALS['wgParamDefinitions'] = [
	'boolean' => [
		'string-parser' => '\ValueParsers\BoolParser',
		'validation-callback' => 'is_bool',
	],
	'float' => [
		'string-parser' => '\ValueParsers\FloatParser',
		'validation-callback' => function( $value ) {
			return is_float( $value ) || is_int( $value );
		},
		'validator' => '\ValueValidators\RangeValidator',
	],
	'integer' => [
		'string-parser' => '\ValueParsers\IntParser',
		'validation-callback' => 'is_int',
		'validator' => '\ValueValidators\RangeValidator',
	],
	'string' => [
		'validator' => '\ValueValidators\StringValidator',
		'definition' => '\ParamProcessor\Definition\StringParam',
	],
	'dimension' => [
		'definition' => '\ParamProcessor\Definition\DimensionParam',
		'validator' => '\ValueValidators\DimensionValidator',
	],
];
