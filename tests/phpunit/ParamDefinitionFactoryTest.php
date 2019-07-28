<?php

namespace ParamProcessor\Tests;

use ParamProcessor\ParamDefinition;
use ParamProcessor\ParamDefinitionFactory;
use PHPUnit\Framework\TestCase;
use ValueParsers\IntParser;
use ValueParsers\NullParser;
use ValueParsers\StringParser;
use ValueValidators\NullValidator;
use ValueValidators\RangeValidator;

/**
 * @covers \ParamProcessor\ParamDefinitionFactory
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinitionFactoryTest extends TestCase {

	public function testCanConstruct() {
		new ParamDefinitionFactory();
		$this->assertTrue( true );
	}

	public function testNewDefinitionFromArray() {
		$definition = ParamDefinitionFactory::newDefault()->newDefinitionFromArray(
			[
				'name' => 'some-list',
				'type' => 'integer',
				'message' => 'test-message',
				'islist' => true
			]
		);

		$this->assertSame( 'some-list', $definition->getName() );
		$this->assertSame( 'integer', $definition->getType() );
		$this->assertSame( 'test-message', $definition->getMessage() );
		$this->assertTrue( $definition->isList() );
		$this->assertTrue( $definition->isRequired() );
	}

	public function testNewDefinition() {
		$definition = ParamDefinitionFactory::newDefault()->newDefinition(
			'integer',
			'some-list',
			null,
			'test-message',
			true
		);

		$this->assertSame( 'some-list', $definition->getName() );
		$this->assertSame( 'integer', $definition->getType() );
		$this->assertSame( 'test-message', $definition->getMessage() );
		$this->assertTrue( $definition->isList() );
		$this->assertTrue( $definition->isRequired() );
	}

	public function testNewDefinitionFromArray_typeDefaultsToString() {
		$this->assertSame( 'string', $this->newBasicParamFromArray()->getType() );
	}

	private function newBasicParamFromArray(): ParamDefinition {
		return ParamDefinitionFactory::newDefault()->newDefinitionFromArray(
			[
				'name' => 'irrelevant',
				'message' => 'irrelevant'
			]
		);
	}

	public function testNewDefinitionFromArray_isListDefaultsToFalse() {
		$this->assertFalse( $this->newBasicParamFromArray()->isList() );
	}

	public function testNewDefinitionFromArray_isRequiredDefaultsToTrue() {
		$this->assertTrue( $this->newBasicParamFromArray()->isRequired() );
	}

	public function testNewDefinitionFromArray_optionsAreSet() {
		$arrayDefinition = [
			'name' => 'some-list',
			'type' => 'integer',
			'message' => 'test-message',
			'islist' => true
		];

		$definition = ParamDefinitionFactory::newDefault()->newDefinitionFromArray( $arrayDefinition );

		$this->assertSame( $arrayDefinition, $definition->getOptions() );
	}

	public function testRegisterType_defaultsAreSet() {
		$factory = ParamDefinitionFactory::newDefault();

		$factory->registerType(
			'kitten',
			[]
		);

		$this->assertSame(
			NullParser::class,
			$factory->getComponentForType( 'kitten', 'string-parser' )
		);

		$this->assertSame(
			NullParser::class,
			$factory->getComponentForType( 'kitten', 'typed-parser' )
		);

		$this->assertSame(
			NullValidator::class,
			$factory->getComponentForType( 'kitten', 'validator' )
		);

		$this->assertNull(
			$factory->getComponentForType( 'kitten', 'validation-callback' )
		);
	}

	public function testRegisterType_parametersAreUsed() {
		$factory = ParamDefinitionFactory::newDefault();

		$factory->registerType(
			'kitten',
			[
				'string-parser' => 'KittenParser',
				'validation-callback' => 'is_int',
				'validator' => 'KittenValidator',
			]
		);

		$this->assertSame(
			'KittenParser',
			$factory->getComponentForType( 'kitten', 'string-parser' )
		);

		$this->assertSame(
			'KittenValidator',
			$factory->getComponentForType( 'kitten', 'validator' )
		);

		$this->assertSame(
			'is_int',
			$factory->getComponentForType( 'kitten', 'validation-callback' )
		);
	}

}
