<?php

declare( strict_types = 1 );

namespace ParamProcessor\Tests\Unit\PackagePrivate;

use ParamProcessor\PackagePrivate\DimensionParser;
use PHPUnit\Framework\TestCase;
use ValueParsers\ParseException;
use ValueParsers\ParserOptions;

class DimensionParserTest extends TestCase {

	public function testParserExceptionOnNonString() {
		$this->expectException( ParseException::class );
		$this->parse( 32202 );
	}

	private function parse( $input ) {
		return ( new DimensionParser() )->parse( $input );
	}

	/**
	 * @dataProvider validDimensionProvider
	 */
	public function testParsingValidInputs( string $input, string $expected ) {
		$this->assertSame( $expected, $this->parse( $input ) );
	}

	public function validDimensionProvider() {
		yield [ '10px', '10px' ];
		yield [ '10ex', '10ex' ];
		yield [ '10em', '10em' ];
		yield [ '2.5px', '2.5px' ];

		yield [ '10 px', '10px' ];
		yield [ '10  px', '10px' ];
		yield [ ' 10  px ', '10px' ];

		yield [ '10', '10px' ];
		yield [ '2.5', '2.5px' ];
	}

	public function testAlternateDefaultUnit() {
		$this->assertSame(
			'1%',
			( new DimensionParser( new ParserOptions( [ DimensionParser::DEFAULT_UNIT => '%' ] ) ) )->parse( '1' )
		);
	}

}
