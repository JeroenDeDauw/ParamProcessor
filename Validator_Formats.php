<?php
/**
 * File holding the ValidatorFormats class.
 *
 * @file ValidatorFormats.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

/**
 * Class holding variouse static methods for the appliance of output formats.
 *
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */
final class ValidatorFormats {
	
	public static function format_array( &$value ) {
		if (! is_array($value)) $value = array($value);	
	}
	
	public static function format_list( &$value, $delimiter = ',', $wrapper = '' ) {
		if (! is_array($value)) $value = array($value);
		$value =  $wrapper . implode($wrapper . $delimiter . $wrapper, $value) . $wrapper;	
	}

	public static function format_boolean( &$value ) {
		if (is_array($value)) {
			$boolArray = array();
			foreach ($value as $item) $boolArray[] = $value == 'yes';
			$value = $boolArray;
		}
		else {
			$value == 'yes';
		}
	}

	public static function format_string( &$value ) {
		if (is_array($value)) {
			global $wgLang;
			$value = $wgLang->listToText($value);
		}		
	}	
	
}