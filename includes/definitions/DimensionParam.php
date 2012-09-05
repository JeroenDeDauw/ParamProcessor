<?php

/**
 * Defines the dimension parameter type.
 * This parameter describes the size of a dimension (ie width) in some unit (ie px) or a percentage.
 * Specifies the type specific validation and formatting logic.
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
 * @ingroup Validator
 * @ingroup ParamDefinition
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DimensionParam extends ParamDefinition {

	/**
	 * Formats the parameter value to it's final result.
	 * @see ParamDefinition::formatValue
	 *
	 * @since 1.0
	 *
	 * @param $value mixed
	 * @param $param IParam
	 * @param $definitions array of IParamDefinition
	 * @param $params array of iParam
	 *
	 * @return mixed
	 * @throws MWException
	 */
	protected function formatValue( $value, IParam $param, array &$definitions, array $params ) {
		if ( $value === 'auto' ) {
			return $value;
		}

		$validator = $this->getValueValidator();

		if ( get_class( $validator ) === 'DimensionValidator' ) {
			foreach ( $this->getValueValidator()->getAllowedUnits() as $unit ) {
				if ( $unit !== '' && in_string( $unit, $value ) ) {
					return $value;
				}
			}

			return $value . $this->getValueValidator()->getDefaultUnit();
		}
		else {
			throw new MWException( 'ValueValidator of a DimensionParam should be a DimensionValidator' );
		}
	}

}
