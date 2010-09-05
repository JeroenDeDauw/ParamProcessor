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

define( 'Validator_VERSION', '0.4 alpha-3' );

// Constants indicating the strictness of the parameter validation.
define( 'Validator_ERRORS_NONE', 0 );
define( 'Validator_ERRORS_LOG', 1 );
define( 'Validator_ERRORS_WARN', 2 );
define( 'Validator_ERRORS_SHOW', 3 );
define( 'Validator_ERRORS_STRICT', 4 );

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

// This function has been deprecated in 1.16, but needed for earlier versions.
// It's present in 1.16 as a stub, but lets check if it exists in case it gets removed at some point.
if ( function_exists( 'wfLoadExtensionMessages' ) ) {
	wfLoadExtensionMessages( 'Validator' );
}

// Autoload the general classes.
$incDir = dirname( __FILE__ ) . '/includes/';
$wgAutoloadClasses['ListParameter'] 		= $incDir . 'ListParameter.php';
$wgAutoloadClasses['Parameter'] 			= $incDir . 'Parameter.php';
$wgAutoloadClasses['ParserHook'] 			= $incDir . 'ParserHook.php';
$wgAutoloadClasses['Validator'] 			= $incDir . 'Validator.php';
$wgAutoloadClasses['TopologicalSort'] 		= $incDir . 'TopologicalSort.php';
$wgAutoloadClasses['ValidationFormats'] 	= $incDir . 'ValidationFormats.php';
$wgAutoloadClasses['ValidationFunctions'] 	= $incDir . 'ValidationFunctions.php';
$wgAutoloadClasses['ValidationManager'] 	= $incDir . 'ValidationManager.php'; // TODO: remove
$wgAutoloadClasses['ValidatorError']		= $incDir . 'Validator_Error.php';
$wgAutoloadClasses['ValidatorErrorHandler']	= $incDir . 'Validator_ErrorHandler.php';
$wgAutoloadClasses['ValidatorListErrors'] 	= $incDir . 'parserHooks/Validator_ListErrors.php';
unset( $incDir );

// Include the settings file.
require_once 'Validator_Settings.php';