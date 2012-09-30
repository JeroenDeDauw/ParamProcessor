<?php

/**
 * Initialization file for the Validator extension.
 * Extension documentation: http://www.mediawiki.org/wiki/Extension:Validator
 *
 * You will be validated. Resistance is futile.
 *
 * @file Validator.php
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * This documenation group collects source code files belonging to Validator.
 *
 * Please do not use this group name for other code.
 *
 * @defgroup Validator Validator
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.16c', '<' ) ) {
	die( '<b>Error:</b> This version of Validator requires MediaWiki 1.16 or above.' );
}

if ( defined( 'Validator_VERSION' ) ) {
	die( '<b>Error:</b> Tried to include Validator a second time. Please make sure you are including it before any extensions that make use of it.' );
}

// Include the DataValues extension if that hasn't been done yet, since it's required for Validator to work.
if ( !defined( 'DataValues_VERSION' ) ) {
	@include_once( __DIR__ . '/../DataValues/DataValues.php' );
}

$dependencies = array(
	'DataValues_VERSION' => 'DataValues',
	'ValueParsers_VERSION' => 'ValueParsers',
	'DataTypes_VERSION' => 'DataTypes',
);

foreach ( $dependencies as $constant => $name ) {
	if ( !defined( $constant ) ) {
		die(
			'<b>Error:</b> Validator depends on the <a href="https://www.mediawiki.org/wiki/Extension:'
				. $name . '">' . $name . '</a> extension.'
		);
	}
}

unset( $dependencies );


define( 'ParamProcessor_VERSION', '1.0 alpha' );
define( 'Validator_VERSION', ParamProcessor_VERSION ); // @deprecated

// Register the internationalization file.
$wgExtensionMessagesFiles['Validator'] = dirname( __FILE__ ) . '/Validator.i18n.php';
$wgExtensionMessagesFiles['ValidatorMagic'] = dirname( __FILE__ ) . '/Validator.i18n.magic.php';

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Validator',
	'version' => ParamProcessor_VERSION,
	'author' => array( '[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Validator',
	'descriptionmsg' => 'validator-desc',
);

$incDir = dirname( __FILE__ ) . '/includes/';

// Autoload the classes.
$wgAutoloadClasses['ParamProcessor\Hooks']			= $incDir . 'ParamProcessor.hooks.php';
$wgAutoloadClasses['ParamProcessor\Settings']		= $incDir . 'ParamProcessor.settings.php';

// includes
$wgAutoloadClasses['ParamProcessor\Param']				  		= $incDir . 'Param.php';
$wgAutoloadClasses['ParamProcessor\ParamDefinitionFactory']		= $incDir . 'ParamDefinitionFactory.php';
$wgAutoloadClasses['ParamProcessor\ParamDefinition']			= $incDir . 'ParamDefinition.php';
$wgAutoloadClasses['ParamProcessor\Processor']				  	= $incDir . 'Processor.php';
$wgAutoloadClasses['ParamProcessor\Options']					= $incDir . 'Options.php';
$wgAutoloadClasses['ParamProcessor\TopologicalSort']			= $incDir . 'TopologicalSort.php';
$wgAutoloadClasses['ParamProcessor\ValidationError']			= $incDir . 'ValidationError.php';
$wgAutoloadClasses['ParamProcessor\ValidationErrorHandler']	 	= $incDir . 'ValidationErrorHandler.php';
$wgAutoloadClasses['ParamProcessor\IParam']				  		= $incDir . 'IParam.php';
$wgAutoloadClasses['ParamProcessor\IParamDefinition']			= $incDir . 'IParamDefinition.php';

// includes/definitions
$wgAutoloadClasses['ParamProcessor\DimensionParam']		 		= $incDir . 'definitions/DimensionParam.php';
$wgAutoloadClasses['ParamProcessor\ParamDefinition']		 	= $incDir . 'definitions/ParamDefinition.php';
$wgAutoloadClasses['ParamProcessor\StringParam']		 		= $incDir . 'definitions/StringParam.php';

class_alias( 'ParamProcessor\ParamDefinitionFactory', 'ParamDefinitionFactory' );
class_alias( 'ParamProcessor\ParamDefinition', 'ParamDefinition' );
class_alias( 'ParamProcessor\IParamDefinition', 'IParamDefinition' );
class_alias( 'ParamProcessor\Processor', 'Validator' );
class_alias( 'ParamProcessor\DimensionParam', 'DimensionParam' );
class_alias( 'ParamProcessor\StringParam', 'StringParam' );

class_alias( 'ParamProcessor\ValidationError', 'ValidationError' ); // Deprecated since 1.0, removal in 1.2



// tests
$wgAutoloadClasses['Validator\Test\NumericParamTest']		= dirname( __FILE__ ) . '/tests/definitions/NumericParamTest.php';
$wgAutoloadClasses['Validator\Test\ParamDefinitionTest']	= dirname( __FILE__ ) . '/tests/definitions/ParamDefinitionTest.php';

// utils
$wgAutoloadClasses['ParserHook']				 	= $incDir . 'utils/ParserHook.php';
$wgAutoloadClasses['ParameterInput']			 	= $incDir . 'utils/ParameterInput.php';
$wgAutoloadClasses['ValidatorDescribe']		  		= $incDir . 'utils/Validator_Describe.php';
$wgAutoloadClasses['ValidatorListErrors']			= $incDir . 'utils/Validator_ListErrors.php';

# Registration of the listerrors parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorListErrors::staticInit';

# Registration of the describe parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorDescribe::staticInit';

// Since 0.4.8
$wgHooks['UnitTestsList'][] = 'ParamProcessor\Hooks::registerUnitTests';



$egValidatorSettings = array();

$egParamDefinitions = array(
	'boolean' => array( // Parameter::TYPE_BOOLEAN
		'string-parser' => 'BoolParser',
		'validation-callback' => 'is_bool',
	),
	'float' => array( // Parameter::TYPE_FLOAT
		'string-parser' => 'FloatParser',
		'validation-callback' => 'is_float',
		'validator' => 'RangeValidator',
	),
	'integer' => array( // Parameter::TYPE_INTEGER
		'string-parser' => 'IntParser',
		'validation-callback' => 'is_int',
		'validator' => 'RangeValidator',
	),
	'string' => array( // Parameter::TYPE_STRING
		'validator' => 'StringValidator',
		'definition' => 'StringParam',
	),
	'title' => array( // Parameter::TYPE_TITLE
		'string-parser' => 'TitleParser',
		'validator' => 'TitleValidator',
	),
	'dimension' => array(
		'definition' => 'DimensionParam',
		'validator' => 'DimensionValidator',
	),
);


unset( $incDir );