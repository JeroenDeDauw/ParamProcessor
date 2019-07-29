<?php

namespace ParamProcessor\Tests\Unit;

use ParamProcessor\Param;
use ParamProcessor\ParamDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ParamAliasTest extends TestCase {

	public function testListParamProcessingWithEmptyListAsDefault() {
		$definition = new ParamDefinition( 'string', 'something' );
		$param = new Param( $definition );
		$this->assertTrue( $param->isRequired() );
	}

}
