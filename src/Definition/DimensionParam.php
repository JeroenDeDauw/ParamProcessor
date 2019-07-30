<?php

namespace ParamProcessor\Definition;

use Exception;
use ParamProcessor\ParamDefinition;
use ParamProcessor\IParam;
use ParamProcessor\IParamDefinition;
use ValueValidators\DimensionValidator;

/**
 * Defines the dimension parameter type.
 * This parameter describes the size of a dimension (ie width) in some unit (ie px) or a percentage.
 * Specifies the type specific validation and formatting logic.
 *
 * TODO: this class is silly, should be handled by a dedicated formatting object/function.
 *
 * @deprecated since 1.7
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
	 * @param mixed $value
	 * @param IParam $param
	 * @param IParamDefinition[] $definitions
	 * @param IParam[] $params
	 *
	 * @return mixed
	 * @throws Exception
	 */
	protected function formatValue( $value, IParam $param, array &$definitions, array $params ) {
		if ( $value === 'auto' ) {
			return $value;
		}

		/**
		 * @var DimensionValidator $validator
		 */
		$validator = $this->getValueValidator();

		if ( $validator instanceof DimensionValidator ) {
			foreach ( $validator->getAllowedUnits() as $unit ) {
				if ( $unit !== '' && strpos( $value, $unit ) !== false ) {
					return $value;
				}
			}

			return $value . $validator->getDefaultUnit();
		}

		throw new Exception(
			'ValueValidator of a DimensionParam should be a ValueValidators\DimensionValidator and not a '
				. get_class( $validator )
		);
	}

}
