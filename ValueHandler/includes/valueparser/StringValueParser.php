<?php

/**
 * ValueParser that parses the string representation of something.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @since 1.0
 *
 * @file
 * @ingroup ValueHandler
 * @ingroup ValueParser
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
abstract class StringValueParser implements ValueParser {

	/**
	 * @see ValueParser::parse
	 *
	 * @since 1.0
	 *
	 * @param mixed $value
	 *
	 * @return ValueParserResult
	 */
	public function parse( $value ) {
		if ( is_string( $value ) ) {
			return $this->stringParse( $value );
		}
		else {
			return ValueParserResultObject::newError( 'Not a string' ); // TODO
		}
	}

	/**
	 * Parses the provided string and returns the result.
	 *
	 * @since 1.0
	 *
	 * @param string $value
	 *
	 * @return ValueParserResult
	 */
	protected abstract function stringParse( $value );

}
