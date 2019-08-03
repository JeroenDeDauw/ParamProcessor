<?php

declare( strict_types = 1 );

namespace ParamProcessor;

use ParamProcessor\Definition\StringParam;
use ParamProcessor\PackagePrivate\DimensionParser;
use ParamProcessor\PackagePrivate\ParamType;
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
	 * @var ParamType[]
	 */
	private $types = [];

	/**
	 * @param array[] $typeSpecs
	 */
	public function __construct( array $typeSpecs = [] ) {
		foreach ( $typeSpecs as $typeName => $typeSpec ) {
			$this->addType( $typeName, $typeSpec );
		}
	}

	/**
	 * @since 1.8
	 */
	public function addType( string $typeName, array $typeSpec ) {
		$this->types[$typeName] = ParamType::newFromArray( $typeName, $typeSpec );
	}

	/**
	 * Package private
	 */
	public function hasType( string $typeName ): bool {
		return array_key_exists( $typeName, $this->types );
	}

	/**
	 * Package private
	 */
	public function getType( string $typeName ): ParamType {
		return $this->types[$typeName];
	}

	/**
	 * @since 1.8
	 */
	public static function newCoreTypes(): self {
		return new self( self::getCoreTypes() );
	}

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
				'string-parser' => DimensionParser::class,
				'validator' => DimensionValidator::class,
			],
		];
	}

}
