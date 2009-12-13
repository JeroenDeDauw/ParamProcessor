<?php
/**
 * File holding the ValidatorFunctions class.
 *
 * @file ValidatorFunctions.php
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

/**
 * Class holding variouse static methods for the validation of parameters that have to comply to cetrain criteria.
 * Functions are called by Validator with the parameters $value, $arguments, where $arguments is optional.
 *
 * @ingroup Validator
 *
 * @author Jeroen De Dauw
 */
final class ValidatorFunctions {

	/**
	 * Returns whether the provided value, which must be a number, is within a certain range. Upper bound not included.
	 *
	 * @param $value
	 * @param array $limits
	 *
	 * @return boolean
	 */
	public static function in_range( $value, array $limits ) {
		if ( ! is_numeric( $value ) ) return false;
		$value = (int)$value;
		return ( $value >= $limits[0] && $value < $limits[1] ) || ( $value < $limits[0] And $value >= $limits[1] );
	}

	/**
	 * Returns whether the string value is not empty. Not empty is defined as having at least one character after trimming.
	 *
	 * @param $value
	 *
	 * @return boolean
	 */
	public static function not_empty( $value ) {
		return strlen( trim( $value ) ) > 0;
	}
	
	/**
	 * Returns whether a variable is an integer or an integer string. Uses the native PHP function.
	 *
	 * @param $value
	 *
	 * @return boolean
	 */
	public static function is_integer( $value ) {
		return ctype_digit( (string)$value );
	}	

	/**
	 * Returns if all items of the first array are present in the second one.
	 *
	 * @param array $needles
	 * @param array $haystack
	 *
	 * @return boolean
	 */
	public static function all_in_array( array $needles, array $haystack ) {
		$true = true;
		foreach ( $needles as $needle ) {
			if ( ! in_array( $needle , $haystack ) ) {
				$true = false;
				break;
			}
		}
		return $true;
	}

	/**
	 * Returns if any items of the first array are present in the second one.
	 *
	 * @param array $needles
	 * @param array $haystack
	 *
	 * @return boolean
	 */
	public static function any_in_array( array $needles, array $haystack ) {
		$true = false;
		foreach ( $needles as $needle ) {
			if ( in_array( $needle , $haystack ) ) {
				$true = true;
				break;
			}
		}
		return $true;
	}
}
