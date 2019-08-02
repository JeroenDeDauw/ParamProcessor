<?php

declare( strict_types = 1 );

namespace ParamProcessor;

use ParamProcessor\Definition\DimensionParam;
use ParamProcessor\Definition\StringParam;
use ValueParsers\BoolParser;
use ValueParsers\FloatParser;
use ValueParsers\IntParser;
use ValueValidators\DimensionValidator;
use ValueValidators\RangeValidator;
use ValueValidators\StringValidator;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParameterTypes {

	/**
	 * @since 1.7
	 */
	public const BOOLEAN = 'boolean';
	public const FLOAT = 'float';
	public const INTEGER = 'integer';
	public const STRING = 'string';
	public const DIMENSION = 'dimension';

	/**
	 * @since 1.4
	 */
	public static function getCoreTypes(): array {
		return [
			self::BOOLEAN => [
				'string-parser' => BoolParser::class,
				'validation-callback' => 'is_bool',
			],
			self::FLOAT => [
				'string-parser' => FloatParser::class,
				'validation-callback' => function( $value ) {
					return is_float( $value ) || is_int( $value );
				},
				'validator' => RangeValidator::class,
			],
			self::INTEGER => [
				'string-parser' => IntParser::class,
				'validation-callback' => 'is_int',
				'validator' => RangeValidator::class,
			],
			self::STRING => [
				'validator' => StringValidator::class,
				'definition' => StringParam::class,
			],
			self::DIMENSION => [
				'definition' => DimensionParam::class,
				'validator' => DimensionValidator::class,
			],
		];
	}

}
