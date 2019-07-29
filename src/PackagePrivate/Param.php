<?php

namespace ParamProcessor\PackagePrivate;

use Exception;
use ParamProcessor\IParam;
use ParamProcessor\IParamDefinition;
use ParamProcessor\Options;
use ParamProcessor\ParamDefinition;
use ParamProcessor\ParamDefinitionFactory;
use ParamProcessor\ProcessingError;
use ValueParsers\NullParser;
use ValueParsers\ParseException;
use ValueParsers\ValueParser;

/**
 * Package private!
 *
 * Parameter class, representing the "instance" of a parameter.
 * Holds a ParamDefinition, user provided input (name & value) and processing state.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Param implements IParam {

	/**
	 * Indicates whether parameters not found in the criteria list
	 * should be stored in case they are not accepted. The default is false.
	 *
	 * @since 1.0
	 *
	 * @var boolean
	 */
	public static $accumulateParameterErrors = false;

	/**
	 * The original parameter name as provided by the user. This can be the
	 * main name or an alias.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	protected $originalName;

	/**
	 * The original value as provided by the user. This is mainly retained for
	 * usage in error messages when the parameter turns out to be invalid.
	 *
	 * @since 1.0
	 *
	 * @var string
	 */
	protected $originalValue;

	/**
	 * The value of the parameter.
	 *
	 * @since 1.0
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Keeps track of how many times the parameter has been set by the user.
	 * This is used to detect overrides and for figuring out a parameter is missing.
	 *
	 * @since 1.0
	 *
	 * @var integer
	 */
	protected $setCount = 0;

	/**
	 * List of validation errors for this parameter.
	 *
	 * @since 1.0
	 *
	 * @var ProcessingError[]
	 */
	protected $errors = [];

	/**
	 * Indicates if the parameter was set to it's default.
	 *
	 * @since 1.0
	 *
	 * @var boolean
	 */
	protected $defaulted = false;

	/**
	 * @since 1.0
	 *
	 * @var ParamDefinition
	 */
	protected $definition;

	/**
	 * Constructor.
	 *
	 * @since 1.0
	 *
	 * @param IParamDefinition $definition
	 */
	public function __construct( IParamDefinition $definition ) {
		$this->definition = $definition;
	}

	/**
	 * Sets and cleans the original value and name.
	 * @see IParam::setUserValue
	 *
	 * @since 1.0
	 *
	 * @param string $paramName
	 * @param string $paramValue
	 * @param Options $options
	 *
	 * @return boolean
	 */
	public function setUserValue( $paramName, $paramValue, Options $options ) {
		if ( $this->setCount > 0 && !$options->acceptOverriding() ) {
			// TODO
			return false;
		}
		else {
			$this->originalName = $paramName;
			$this->originalValue = $paramValue;

			$this->cleanValue( $options );

			$this->setCount++;

			return true;
		}
	}

	/**
	 * @since 1.0
	 *
	 * @param mixed $value
	 */
	public function setValue( $value ) {
		$this->value = $value;
	}

	/**
	 * Sets the $value to a cleaned value of $originalValue.
	 *
	 * @since 1.0
	 *
	 * @param Options $options
	 */
	protected function cleanValue( Options $options ) {
		if ( $this->definition->isList() ) {
			$this->value = explode( $this->definition->getDelimiter(), $this->originalValue );
		}
		else {
			$this->value = $this->originalValue;
		}

		if ( $this->shouldTrim( $options ) ) {
			$this->trimValue();
		}

		if ( $this->shouldLowercase( $options ) ) {
			$this->lowercaseValue();
		}
	}

	private function shouldTrim( Options $options ): bool {
		$trim = $this->definition->trimDuringClean();

		if ( $trim === true ) {
			return true;
		}

		return is_null( $trim ) && $options->trimValues();
	}

	private function trimValue() {
		if ( is_string( $this->value ) ) {
			$this->value = trim( $this->value );
		}
		elseif ( $this->definition->isList() ) {
			foreach ( $this->value as &$element ) {
				if ( is_string( $element ) ) {
					$element = trim( $element );
				}
			}
		}
	}

	private function shouldLowercase( Options $options ): bool {
		if ( $options->lowercaseValues() ) {
			return true;
		}

		$definitionOptions = $this->definition->getOptions();

		return array_key_exists( 'tolower', $definitionOptions ) && $definitionOptions['tolower'];
	}

	private function lowercaseValue() {
		if ( $this->definition->isList() ) {
			foreach ( $this->value as &$element ) {
				if ( is_string( $element ) ) {
					$element = strtolower( $element );
				}
			}
		}
		elseif ( is_string( $this->value ) ) {
			$this->value = strtolower( $this->value );
		}
	}

	/**
	 * Parameter processing entry point.
	 * Processes the parameter. This includes parsing, validation and additional formatting.
	 *
	 * @since 1.0
	 *
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 * @param Options $options
	 *
	 * @throws Exception
	 */
	public function process( array &$definitions, array $params, Options $options ) {
		if ( $this->setCount == 0 ) {
			if ( $this->definition->isRequired() ) {
				// This should not occur, so throw an exception.
				throw new Exception( 'Attempted to validate a required parameter without first setting a value.' );
			}
			else {
				$this->setToDefault();
			}
		}
		else {
			$this->parseAndValidate( $options );
		}

		if ( !$this->hasFatalError() && ( $this->definition->shouldManipulateDefault() || !$this->wasSetToDefault() ) ) {
			$this->definition->format( $this, $definitions, $params );
		}
	}

	public function getValueParser( Options $options ): ValueParser {
		$parser = $this->definition->getValueParser();

		if ( get_class( $parser ) === NullParser::class ) {
			$parserType = $options->isStringlyTyped() ? 'string-parser' : 'typed-parser';

			// TODO: inject factory
			$parserClass = ParamDefinitionFactory::singleton()->getComponentForType( $this->definition->getType(), $parserType );

			if ( $parserClass !== NullParser::class ) {
				$parser = new $parserClass( new \ValueParsers\ParserOptions() );
			}
		}

		return $parser;
	}

	/**
	 * @since 1.0
	 *
	 * @param Options $options
	 */
	protected function parseAndValidate( Options $options ) {
		$parser = $this->getValueParser( $options );

		if ( $this->definition->isList() ) {
			$values = [];

			foreach ( $this->getValue() as $value ) {
				$parsedValue = $this->parseAndValidateValue( $parser, $value );

				if ( is_array( $parsedValue ) ) {
					$values[] = $parsedValue[0];
				}
			}

			$this->value = $values;
		}
		else {
			$parsedValue = $this->parseAndValidateValue( $parser, $this->getValue() );

			if ( is_array( $parsedValue ) ) {
				$this->value = $parsedValue[0];
			}
		}

		$this->setToDefaultIfNeeded();
	}

	/**
	 * Parses and validates the provided with with specified parser.
	 * The result is returned in an array on success. On fail, false is returned.
	 * The result is wrapped in an array since we need to be able to distinguish
	 * between the method returning false and the value being false.
	 *
	 * Parsing and validation errors get added to $this->errors.
	 *
	 * @since 1.0
	 *
	 * @param ValueParser $parser
	 * @param mixed $value
	 *
	 * @return array|bool
	 */
	protected function parseAndValidateValue( ValueParser $parser, $value ) {
		try {
			$value = $parser->parse( $value );
		}
		catch ( ParseException $parseException ) {
			$this->registerProcessingError( $parseException->getMessage() );
			return false;
		}

		if ( $value instanceof \DataValues\DataValue ) {
			$value = $value->getValue();
		}

		$this->validateValue( $value );

		return [ $value ];
	}

	protected function registerProcessingError( string $message ) {
		$this->errors[] = $this->newProcessingError( $message );
	}

	protected function newProcessingError( string $message ): ProcessingError {
		$severity = $this->isRequired() ? ProcessingError::SEVERITY_FATAL : ProcessingError::SEVERITY_NORMAL;
		return new ProcessingError( $message, $severity );
	}

	/**
	 * @since 1.0
	 *
	 * @param mixed $value
	 */
	protected function validateValue( $value ) {
		$validationCallback = $this->definition->getValidationCallback();

		if ( $validationCallback !== null && $validationCallback( $value ) !== true ) {
			$this->registerProcessingError( 'Validation callback failed' );
		}
		else {
			$validator = $this->definition->getValueValidator();
			if ( method_exists( $validator, 'setOptions' ) ) {
				$validator->setOptions( $this->definition->getOptions() );
			}
			$validationResult = $validator->validate( $value );

			if ( !$validationResult->isValid() ) {
				foreach ( $validationResult->getErrors() as $error ) {
					$this->registerProcessingError( $error->getText() );
				}
			}
		}
	}

	/**
	 * Sets the parameter value to the default if needed.
	 *
	 * @since 1.0
	 */
	protected function setToDefaultIfNeeded() {
		if ( $this->shouldSetToDefault() ) {
			$this->setToDefault();
		}
	}

	private function shouldSetToDefault(): bool {
		if ( $this->hasFatalError() ) {
			return false;
		}

		if ( $this->definition->isList() ) {
			return $this->errors !== [] && $this->value === [];
		}

		return $this->errors !== [];
	}

	/**
	 * Returns the original use-provided name.
	 *
	 * @since 1.0
	 *
	 * @throws Exception
	 * @return string
	 */
	public function getOriginalName(): string {
		if ( $this->setCount == 0 ) {
			throw new Exception( 'No user input set to the parameter yet, so the original name does not exist' );
		}
		return $this->originalName;
	}

	/**
	 * Returns the original use-provided value.
	 *
	 * @since 1.0
	 *
	 * @throws Exception
	 * @return mixed
	 */
	public function getOriginalValue() {
		if ( $this->setCount == 0 ) {
			throw new Exception( 'No user input set to the parameter yet, so the original value does not exist' );
		}
		return $this->originalValue;
	}

	/**
	 * Returns all validation errors that occurred so far.
	 *
	 * @since 1.0
	 *
	 * @return ProcessingError[]
	 */
	public function getErrors() {
		return $this->errors;
	}

	/**
	 * Sets the parameter value to the default.
	 *
	 * @since 1.0
	 */
	protected function setToDefault() {
		$this->defaulted = true;
		$this->value = $this->definition->getDefault();
	}

	public function wasSetToDefault(): bool {
		return $this->defaulted;
	}

	public function hasFatalError(): bool {
		foreach ( $this->errors as $error ) {
			if ( $error->isFatal() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the IParamDefinition this IParam was constructed from.
	 *
	 * @since 1.0
	 *
	 * @return IParamDefinition
	 */
	public function getDefinition() {
		return $this->definition;
	}

	/**
	 * Returns the parameters value.
	 *
	 * @since 1.0
	 *
	 * @return mixed
	 */
	public function &getValue() {
		return $this->value;
	}

	/**
	 * Returns if the parameter is required or not.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function isRequired() {
		return $this->definition->isRequired();
	}

	/**
	 * Returns if the name of the parameter.
	 *
	 * @since 1.0
	 *
	 * @return string
	 */
	public function getName() {
		return $this->definition->getName();
	}

	/**
	 * Returns the parameter name aliases.
	 *
	 * @since 1.0
	 *
	 * @return string[]
	 */
	public function getAliases(): array {
		return $this->definition->getAliases();
	}

}