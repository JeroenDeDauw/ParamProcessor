<?php

namespace ParamProcessor\Tests\Integration\Types;

use ParamProcessor\ParamDefinitionFactory;
use ParamProcessor\ProcessingResult;
use ParamProcessor\Processor;
use PHPUnit\Framework\TestCase;

abstract class TypeTestBase extends TestCase {

	protected function process( array $definitionArrays, array $userInput ): ProcessingResult {
		$processor = Processor::newDefault();

		$processor->setParameters( $userInput );
		$processor->setParameterDefinitions(
			ParamDefinitionFactory::newDefault()->newDefinitionsFromArrays( $definitionArrays )
		);

		return $processor->processParameters();
	}

}
