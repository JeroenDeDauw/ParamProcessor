<?php

namespace ParamProcessor\PackagePrivate;

use ParamProcessor\ParamDefinition;
use ValueParsers\NullParser;
use ValueValidators\NullValidator;

/**
 * Package private
 */
class ParamType {

	private $typeId;

	private $className;
	private $stringParser;
	private $typedParser;
	private $validator;
	private $validationCallback;

	private function __construct( string $typeId ) {
		$this->typeId = $typeId;
	}

	public static function newFromArray( string $typeId, array $spec ): self {
		$type = new self( $typeId );

		$type->className = array_key_exists( 'definition', $spec ) ? $spec['definition'] : ParamDefinition::class;
		$type->stringParser = array_key_exists( 'string-parser', $spec ) ? $spec['string-parser'] : NullParser::class;
		$type->typedParser = array_key_exists( 'typed-parser', $spec ) ? $spec['typed-parser'] : NullParser::class;
		$type->validator = array_key_exists( 'validator', $spec ) ? $spec['validator'] : NullValidator::class;
		$type->validationCallback = array_key_exists( 'validation-callback', $spec ) ? $spec['validation-callback'] : null;

		return $type;
	}

	public function getClassName(): string {
		return $this->className;
	}

	public function getValidatorClass(): string {
		return $this->validator;
	}

	public function getValidationCallback(): ?callable {
		return $this->validationCallback;
	}

	public function getStringParserClass(): string {
		return $this->stringParser;
	}

	public function getTypedParserClass(): string {
		return $this->typedParser;
	}

}
