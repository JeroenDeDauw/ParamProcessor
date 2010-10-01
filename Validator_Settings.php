<?php

/**
 * File defining the settings for the Validator extension
 *
 *                          NOTICE:
 * Changing one of these settings can be done by copieng or cutting it,
 * and placing it in LocalSettings.php, AFTER the inclusion of Validator.
 *
 * @file Validator_Settings.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

# Registration of the listerrors parser hooks.
$wgHooks['ParserFirstCallInit'][] = 'ValidatorListErrors::staticInit';
$wgHooks['LanguageGetMagic'][] = 'ValidatorListErrors::staticMagic';

// TODO: document
$egErrorActions = array(
	ValidationError::SEVERITY_MINOR => ValidationError::ACTION_LOG,
	ValidationError::SEVERITY_LOW => ValidationError::ACTION_WARN,
	ValidationError::SEVERITY_NORMAL => ValidationError::ACTION_SHOW,
	ValidationError::SEVERITY_HIGH => ValidationError::ACTION_DEMAND,
);