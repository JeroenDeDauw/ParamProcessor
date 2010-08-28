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
 * @author Jeroen De Dauw
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

define( 'Validator_VERSION', '0.4 alpha-1' );

// Constants indicating the strictness of the parameter validation.
define( 'Validator_ERRORS_NONE', 0 );
define( 'Validator_ERRORS_LOG', 1 );
define( 'Validator_ERRORS_WARN', 2 );
define( 'Validator_ERRORS_SHOW', 3 );
define( 'Validator_ERRORS_STRICT', 4 );

// Include the settings file.
require_once 'Validator_Settings.php';

// Register the internationalization file.
$wgExtensionMessagesFiles['Validator'] = dirname( __FILE__ ) . '/Validator.i18n.php';

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Validator',
	'version' => Validator_VERSION,
	'author' => array( '[http://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]' ),
	'url' => 'http://www.mediawiki.org/wiki/Extension:Validator',
	'descriptionmsg' => 'validator-desc',
);

// Autoload the general classes.
$wgAutoloadClasses['Validator'] 			= dirname( __FILE__ ) . '/Validator.class.php';
$wgAutoloadClasses['ParserHook'] 			= dirname( __FILE__ ) . '/ParserHook.php';
$wgAutoloadClasses['ValidatorFunctions'] 	= dirname( __FILE__ ) . '/Validator_Functions.php';
$wgAutoloadClasses['ValidatorFormats'] 		= dirname( __FILE__ ) . '/Validator_Formats.php';
$wgAutoloadClasses['ValidatorManager'] 		= dirname( __FILE__ ) . '/Validator_Manager.php';
$wgAutoloadClasses['TopologicalSort'] 		= dirname( __FILE__ ) . '/TopologicalSort.php';