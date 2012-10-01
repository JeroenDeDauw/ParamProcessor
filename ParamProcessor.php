<?php

/**
 * Initialization file for the Validator extension.
 * Extension documentation: http://www.mediawiki.org/wiki/Extension:Validator
 *
 * You will be validated. Resistance is futile.
 *
 * @file
 * @ingroup ParamProcessor
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
$wgExtensionMessagesFiles['Validator'] = __DIR__ . '/Validator.i18n.php';
$wgExtensionMessagesFiles['ValidatorMagic'] = __DIR__ . '/Validator.i18n.magic.php';

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Validator',
	'version' => ParamProcessor_VERSION,
	'author' => array( '[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Validator',
	'descriptionmsg' => 'validator-desc',
);

// Autoload the classes.
$wgAutoloadClasses['ParamProcessor\Hooks']			= __DIR__ . '/ParamProcessor.hooks.php';
$wgAutoloadClasses['ParamProcessor\Settings']		= __DIR__ . '/ParamProcessor.settings.php';

// includes
$wgAutoloadClasses['ParamProcessor\Param']				  		= __DIR__ . '/includes/Param.php';
$wgAutoloadClasses['ParamProcessor\ParamDefinitionFactory']		= __DIR__ . '/includes/ParamDefinitionFactory.php';
$wgAutoloadClasses['ParamProcessor\ParamDefinition']			= __DIR__ . '/includes/ParamDefinition.php';
$wgAutoloadClasses['ParamProcessor\Processor']				  	= __DIR__ . '/includes/Processor.php';
$wgAutoloadClasses['ParamProcessor\Options']					= __DIR__ . '/includes/Options.php';
$wgAutoloadClasses['ParamProcessor\TopologicalSort']			= __DIR__ . '/includes/TopologicalSort.php';
$wgAutoloadClasses['ParamProcessor\ValidationError']			= __DIR__ . '/includes/ValidationError.php';
$wgAutoloadClasses['ParamProcessor\ValidationErrorHandler']	 	= __DIR__ . '/includes/ValidationErrorHandler.php';
$wgAutoloadClasses['ParamProcessor\IParam']				  		= __DIR__ . '/includes/IParam.php';
$wgAutoloadClasses['ParamProcessor\IParamDefinition']			= __DIR__ . '/includes/IParamDefinition.php';

// includes/definitions
$wgAutoloadClasses['ParamProcessor\DimensionParam']		 		= __DIR__ . '/includes/definitions/DimensionParam.php';
$wgAutoloadClasses['ParamProcessor\ParamDefinition']		 	= __DIR__ . '/includes/definitions/ParamDefinition.php';
$wgAutoloadClasses['ParamProcessor\StringParam']		 		= __DIR__ . '/includes/definitions/StringParam.php';

class_alias( 'ParamProcessor\ParamDefinitionFactory', 'ParamDefinitionFactory' );
class_alias( 'ParamProcessor\ParamDefinition', 'ParamDefinition' );
class_alias( 'ParamProcessor\IParamDefinition', 'IParamDefinition' );
class_alias( 'ParamProcessor\Processor', 'Validator' );
class_alias( 'ParamProcessor\DimensionParam', 'DimensionParam' );
class_alias( 'ParamProcessor\StringParam', 'StringParam' );

class_alias( 'ParamProcessor\ValidationError', 'ValidationError' ); // Deprecated since 1.0, removal in 1.2
class_alias( 'ParamProcessor\Options', 'ValidatorOptions' ); // Deprecated since 1.0, removal in 1.2



// tests
$wgAutoloadClasses['ParamProcessor\Test\NumericParamTest']		= __DIR__ . '/tests/definitions/NumericParamTest.php';
$wgAutoloadClasses['ParamProcessor\Test\ParamDefinitionTest']	= __DIR__ . '/tests/definitions/ParamDefinitionTest.php';

// utils
$wgAutoloadClasses['ParserHook']				 	= __DIR__ . '/utils/ParserHook.php';
$wgAutoloadClasses['ParameterInput']			 	= __DIR__ . '/utils/ParameterInput.php';
$wgAutoloadClasses['ValidatorDescribe']		  		= __DIR__ . '/utils/Describe.php';
$wgAutoloadClasses['ValidatorListErrors']			= __DIR__ . '/utils/ListErrors.php';

# Registration of the listerrors parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorListErrors::staticInit';

# Registration of the describe parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorDescribe::staticInit';

// Since 0.4.8
$wgHooks['UnitTestsList'][] = 'ParamProcessor\Hooks::registerUnitTests';



$egValidatorSettings = array();

$egParamDefinitions = array(
	'boolean' => array(
		'string-parser' => '\ValueParsers\BoolParser',
		'validation-callback' => 'is_bool',
	),
	'float' => array(
		'string-parser' => '\ValueParsers\FloatParser',
		'validation-callback' => 'is_float',
		'validator' => '\ValueValidators\RangeValidator',
	),
	'integer' => array(
		'string-parser' => '\ValueParsers\IntParser',
		'validation-callback' => 'is_int',
		'validator' => '\ValueValidators\RangeValidator',
	),
	'string' => array(
		'validator' => '\ValueValidators\StringValidator',
		'definition' => '\ParamProcessor\StringParam',
	),
	'title' => array(
		'string-parser' => '\ValueParsers\TitleParser',
		'validator' => '\ValueValidators\TitleValidator',
	),
	'dimension' => array(
		'definition' => '\ParamProcessor\DimensionParam',
		'validator' => '\ValueValidators\DimensionValidator',
	),
);