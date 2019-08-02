<?php

namespace ParamProcessor\Tests\Unit;

use ParamProcessor\ParamDefinition;
use ParamProcessor\ParamDefinitionFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ParamProcessor\ParamDefinitionFactory
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamDefinitionFactoryTest extends TestCase {

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

}
