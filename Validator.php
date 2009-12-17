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

define( 'Validator_VERSION', '0.1' );

// Constants indicating the strictness of the parameter validation.
define( 'Validator_ERRORS_NONE', 0 );
define( 'Validator_ERRORS_WARN', 1 );
define( 'Validator_ERRORS_SHOW', 2 );
define( 'Validator_ERRORS_STRICT', 3 );

$egValidatorIP = $IP . '/extensions/Validator';

// Include the settings file.
require_once( $egValidatorIP . '/Validator_Settings.php' );

// Put the initalization function into the MW extension hook.
$wgExtensionFunctions[] = 'efValidatorSetup';

// Register the internationalization file.
$wgExtensionMessagesFiles['Validator'] = $egValidatorIP . '/Validator.i18n.php';

// Autoload the general classes
$wgAutoloadClasses['Validator'] 			= $egValidatorIP . '/Validator.class.php';
$wgAutoloadClasses['ValidatorFunctions'] 	= $egValidatorIP . '/Validator_Functions.php';
$wgAutoloadClasses['ValidatorManager'] 		= $egValidatorIP . '/Validator_Manager.php';

/**
 * Initialization function for the Validator extension.
 */
function efValidatorSetup() {
	global $wgExtensionCredits;

	wfLoadExtensionMessages( 'Validator' );

	$wgExtensionCredits['other'][] = array(
		'path' => __FILE__,
		'name' => wfMsg( 'validator_name' ),
		'version' => Validator_VERSION,
		'author' => array( '[http://bn2vs.com Jeroen De Dauw]' ),
		'url' => 'http://www.mediawiki.org/wiki/Extension:Validator',
		'description' =>  wfMsg( 'validator-desc' ),
		'descriptionmsg' => 'validator-desc',
	);
}
