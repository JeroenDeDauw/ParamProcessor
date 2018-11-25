<?php

namespace ParamProcessor\Tests;

use ParamProcessor\ParamDefinitionFactory;
use PHPUnit\Framework\TestCase;

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

	// TODO: test other methods

}
