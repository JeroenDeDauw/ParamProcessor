<?php

namespace ParamProcessor;

/**
 * Static class for hooks handled by the Validator extension.
 * 
 * @since 0.4.8
 * 
 * @file
 * @ingroup ParamProcessor
 * 
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class Hooks {
	
	/**
	 * Hook to add PHPUnit test cases.
	 * 
	 * @since 0.4.8
	 * 
	 * @param array $files
	 *
	 * @return boolean
	 */
	public static function registerUnitTests( array &$files ) {
		$testFiles = array(
			'definitions/BoolParam',
			'definitions/DimensionParam',
			'definitions/FloatParam',
			'definitions/IntParam',
			'definitions/StringParam',
			'definitions/TitleParam',

			'ParamDefinitionFactory',
			'Options',
			'Processor',
		);

		foreach ( $testFiles as $file ) {
			$files[] = __DIR__ . '/tests/' . $file . 'Test.php';
		}

		return true;
	}
	
} 
