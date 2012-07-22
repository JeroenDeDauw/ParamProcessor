<?php

/**
 * Object for holding options affecting the behaviour of a Validator object.
 *
 * @since 0.5
 *
 * @file
 * @ingroup Validator
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorOptions {

	protected $name;

	protected $unknownInvalid = true;

	protected $lowercaseNames = true;

	protected $rawStringInputs = true;



	protected $trimValues = false;

	protected $lowercaseValues = false;

}
