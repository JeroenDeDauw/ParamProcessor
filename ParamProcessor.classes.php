<?php

/**
 * Class registration file for the ParamProcessor library.
 *
 * @since 1.0
 *
 * @file
 * @ingroup ParamProcessor
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
return call_user_func( function() {

	// PSR-0 compliant :)

	$classes = array(
		'ParamProcessor\Hooks',
		'ParamProcessor\Settings',

		'ParamProcessor\Param',
		'ParamProcessor\ParamDefinitionFactory',
		'ParamProcessor\ParamDefinition',
		'ParamProcessor\ProcessedParam',
		'ParamProcessor\ProcessingError',
		'ParamProcessor\ProcessingResult',
		'ParamProcessor\Processor',
		'ParamProcessor\MediaWikiTitleValue',
		'ParamProcessor\Options',
		'ParamProcessor\TopologicalSort',
		'ParamProcessor\ProcessingErrorHandler',
		'ParamProcessor\IParam',
		'ParamProcessor\IParamDefinition',
		'ParamProcessor\TitleParser',

		'ParamProcessor\Definition\DimensionParam',
		'ParamProcessor\Definition\StringParam',
	);
	
	$paths = array();

	foreach ( $classes as $class ) {
		$path = 'includes/' . str_replace( '\\', '/', $class ) . '.php';

		$paths[$class] = $path;
	}

	return $paths;

} );
