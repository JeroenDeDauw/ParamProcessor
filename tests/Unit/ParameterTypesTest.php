<?php

namespace ParamProcessor\Tests\Unit;

use ParamProcessor\ParameterTypes;
use PHPUnit\Framework\TestCase;
use ValueParsers\NullParser;
use ValueValidators\NullValidator;

/**
 * @covers \ParamProcessor\ParameterTypes
 */
class ParameterTypesTest extends TestCase {

	public function testAddType_defaultsAreSet() {
		$types = new ParameterTypes();

		$types->addType(
			'kitten',
			[]
		);

		$type = $types->getType( 'kitten' );

		$this->assertSame(
			NullParser::class,
			$type->getStringParserClass()
		);

		$this->assertSame(
			NullParser::class,
			$type->getTypedParserClass()
		);

		$this->assertSame(
			NullValidator::class,
			$type->getValidatorClass()
		);

		$this->assertNull(
			$type->getValidationCallback()
		);
	}

	public function testRegisterType_parametersAreUsed() {
		$types = new ParameterTypes();

		$types->addType(
			'kitten',
			[
				'string-parser' => 'KittenParser',
				'validation-callback' => 'is_int',
				'validator' => 'KittenValidator',
			]
		);

		$type = $types->getType( 'kitten' );

		$this->assertSame(
			'KittenParser',
			$type->getStringParserClass()
		);

		$this->assertSame(
			'KittenValidator',
			$type->getValidatorClass()
		);

		$this->assertSame(
			'is_int',
			$type->getValidationCallback()
		);
	}

}
