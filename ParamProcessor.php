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
 * This documentation group collects source code files belonging to Validator.
 *
 * Please do not use this group name for other code.
 *
 * @defgroup Validator Validator
 */

if ( defined( 'ParamProcessor_VERSION' ) ) {
	// Do not initialize more then once.
	return;
}

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


define( 'ParamProcessor_VERSION', '1.0 beta' );
define( 'Validator_VERSION', ParamProcessor_VERSION ); // @deprecated since 1.0

// Register the internationalization file.
$wgExtensionMessagesFiles['Validator'] = __DIR__ . '/Validator.i18n.php';
$wgExtensionMessagesFiles['ValidatorMagic'] = __DIR__ . '/Validator.i18n.magic.php';

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Validator (ParamProcessor)',
	'version' => ParamProcessor_VERSION,
	'author' => array( '[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'https://www.mediawiki.org/wiki/Extension:ParamProcessor',
	'descriptionmsg' => 'validator-desc',
);

foreach ( include( __DIR__ . '/ParamProcessor.classes.php' ) as $class => $file ) {
	$wgAutoloadClasses[$class] = __DIR__ . '/' . $file;
}


class_alias( 'ParamProcessor\ParamDefinitionFactory', 'ParamDefinitionFactory' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\ParamDefinition', 'ParamDefinition' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\Definition\StringParam', 'StringParam' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\Definition\StringParam', 'ParamProcessor\StringParam' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\IParamDefinition', 'IParamDefinition' ); // Softly deprecated since 1.0, removal in 1.5
class_alias( 'ParamProcessor\Definition\DimensionParam', 'DimensionParam' ); // Softly deprecated since 1.0, removal in 1.5

class_alias( 'ParamProcessor\ProcessingError', 'ProcessingError' ); // Deprecated since 1.0, removal in 1.2
class_alias( 'ParamProcessor\Options', 'ValidatorOptions' ); // Deprecated since 1.0, removal in 1.2
class_alias( 'ParamProcessor\IParam', 'IParam' ); // Deprecated since 1.0, removal in 1.2

/**
 * @deprecated since 1.0, removal in 1.3
 */
class Validator extends ParamProcessor\Processor {

	public function __construct() {
		parent::__construct( new ParamProcessor\Options() );
	}

}


// tests
$wgAutoloadClasses['ParamProcessor\Test\NumericParamTest']		= __DIR__ . '/tests/definitions/NumericParamTest.php';
$wgAutoloadClasses['ParamProcessor\Test\ParamDefinitionTest']	= __DIR__ . '/tests/definitions/ParamDefinitionTest.php';

// utils
$wgAutoloadClasses['ParserHook']				 	= __DIR__ . '/includes/utils/ParserHook.php';
$wgAutoloadClasses['ValidatorDescribe']		  		= __DIR__ . '/includes/utils/Describe.php';
$wgAutoloadClasses['ValidatorListErrors']			= __DIR__ . '/includes/utils/ListErrors.php';

// Registration of the listerrors parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorListErrors::staticInit';

// Registration of the describe parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorDescribe::staticInit';

// Since 0.4.8
$wgHooks['UnitTestsList'][] = 'ParamProcessor\Hooks::registerUnitTests';

$wgDataValues['mediawikititle'] = 'ParamProcessor\MediaWikiTitleValue';

include_once( __DIR__ . '/config/DefaultConfig.php' );
