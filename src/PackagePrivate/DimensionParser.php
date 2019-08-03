<?php

namespace ParamProcessor\PackagePrivate;

use ValueParsers\ParseException;
use ValueParsers\ParserOptions;
use ValueParsers\ValueParser;

/**
 * Package private
 */
class DimensionParser implements ValueParser {

	public const DEFAULT_UNIT = 'defaultunit';

	public const PIXELS = 'px';

	private $defaultUnit;

	public function __construct( ParserOptions $options = null ) {
		$options = $options ?? new ParserOptions();

		$this->defaultUnit = $options->hasOption( self::DEFAULT_UNIT ) ? $options->getOption( self::DEFAULT_UNIT ) : self::PIXELS;
	}

	public function parse( $value ) {
		if ( !is_string( $value ) ) {
			throw new ParseException( 'Not a string' );
		}

		$value = $this->removeWhitespace( $value );

		if ( preg_match( '/^(\d|\.)+$/', $value ) ) {
			$value .= $this->defaultUnit;
		}

		return $value;
	}

	private function removeWhitespace( string $string ): string {
		return preg_replace( '/\s+/', '', $string );
	}

}
