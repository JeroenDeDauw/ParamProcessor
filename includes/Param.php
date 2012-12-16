<?php

namespace ParamProcessor;
use MWException;
use ValueParsers\ValueParser;

/**
 * Parameter class, representing the "instance" of a parameter.
 * Holds a ParamDefinition, user provided input (name & value) and processing state.
 *
 * @since 1.0
 *
 * @file
 * @ingroup ParamProcessor
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class Param implements IParam {

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
	 * @var array of ValidationError
	 */
	protected $errors = array();

	/**
	 * Indicates if the parameter was set to it's default.
	 *
	 * @since 1.0
	 *
	 * @var boolean
	 */
	protected $defaulted = false;

	/**
	 * The definition of the parameter.
	 *
	 * @since 1.0
	 *
	 * @var IParamDefinition
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
		if ( $this->setCount > 0 && !self::$acceptOverriding ) {
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
	 * Sets the value.
	 *
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
	 * TODO: the per-parameter lowercaseing and trimming here needs some thought
	 *
	 * @since 1.0
	 *
	 * @param Options $options
	 */
	protected function cleanValue( Options $options ) {
		$this->value = $this->originalValue;

		if ( $this->definition->isList() ) {
			$this->value = explode( $this->definition->getDelimiter(), $this->value );
		}

		$trim = $this->getDefinition()->trimDuringClean();

		if ( $trim === true || ( is_null( $trim ) && $options->trimValues() ) ) {
			if ( $this->definition->isList() ) {
				foreach ( $this->value as &$element ) {
					if ( is_string( $element ) ) {
						$element = trim( $element );
					}
				}
			}
			elseif ( is_string( $this->value ) ) {
				$this->value = trim( $this->value );
			}
		}

		$definitionOptions = $this->definition->getOptions();

		if ( $options->lowercaseValues() || ( array_key_exists( 'tolower', $definitionOptions ) && $definitionOptions['tolower'] ) ) {
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
	}

	/**
	 * Parameter processing entry point.
	 * @see IParam::process
	 *
	 * @since 1.0
	 *
	 * @param $definitions array of IParamDefinition
	 * @param $params array of IParam
	 * @param Options $options
	 *
	 * @throws MWException
	 */
	public function process( array &$definitions, array $params, Options $options ) {
		if ( $this->setCount == 0 ) {
			if ( $this->definition->isRequired() ) {
				// This should not occur, so throw an exception.
				throw new MWException( 'Attempted to validate a required parameter without first setting a value.' );
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

	/**
	 * @since 1.0
	 *
	 * @param Options $options
	 *
	 * @return ValueParser
	 */
	public function getValueParser( Options $options ) {
		$parser = $this->definition->getValueParser();

		if ( get_class( $parser ) === 'ValueParsers\NullParser' ) {
			$parserType = $options->isStringlyTyped() ? 'string-parser' : 'typed-parser';
			$parserClass = ParamDefinitionFactory::singleton()->getComponentForType( $this->definition->getType(), $parserType );

			if ( $parserClass !== 'ValueParsers\NullParser' ) {
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
			$values = array();

			foreach ( $this->getValue() as $value ) {
				$parsedValue = $this->parseAndValidateValue( $parser, $value );

				if ( is_array( $parsedValue ) ) {
					$values[] = $parsedValue[0];
				}
			}

			$this->setValue( $values );
		}
		else {
			$parsedValue = $this->parseAndValidateValue( $parser, $this->getValue() );

			if ( is_array( $parsedValue ) ) {
				$this->setValue( $parsedValue[0] );
			}
		}

		$this->setToDefaultIfNeeded();
	}

	/**
	 * Parses and validates the provdied with with specified parser.
	 * The result is returned in an array on success. On fail, false is returned.
	 *
	 * Parsing and validattion errors get added to $this->errors.
	 *
	 * @since 1.0
	 *
	 * @param ValueParser $parser
	 * @param mixed $value
	 *
	 * @return array|bool
	 */
	protected function parseAndValidateValue( ValueParser $parser, $value ) {
		$parsingResult = $parser->parse( $value );

		$severity = $this->isRequired() ? ValidationError::SEVERITY_FATAL : ValidationError::SEVERITY_NORMAL;

		if ( $parsingResult->isValid() ) {
			$value = $parsingResult->getValue();

			if ( $value instanceof \DataValues\DataValue ) {
				$value = $value->getValue();
			}

			$validationCallback = $this->definition->getValidationCallback();

			if ( $validationCallback !== null && $validationCallback( $value ) !== true ) {
				$this->errors[] = new ValidationError( 'Validation callback failed', $severity );
			}
			else {
				$validator = $this->definition->getValueValidator();
				$validator->setOptions( $this->definition->getOptions() ); // TODO
				$validationResult = $validator->validate( $value );

				if ( !$validationResult->isValid() ) {
					foreach ( $validationResult->getErrors() as $error ) {
						$this->errors[] = new ValidationError( $error->getText(), $severity );
					}
				}
			}

			return array( $value );
		}
		else {
			$this->errors[] = new ValidationError( $parsingResult->getError()->getText(), $severity );
		}

		return false;
	}

	/**
	 * Sets the parameter value to the default if needed.
	 *
	 * @since 1.0
	 */
	protected function setToDefaultIfNeeded() {
		if ( $this->errors !== array() && !$this->hasFatalError() ) {
			$this->setToDefault();
		}
	}

	/**
	 * Returns the original use-provided name.
	 *
	 * @since 1.0
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getOriginalName() {
		if ( $this->setCount == 0 ) {
			throw new MWException( 'No user input set to the parameter yet, so the original name does not exist' );
		}
		return $this->originalName;
	}

	/**
	 * Returns the original use-provided value.
	 *
	 * @since 1.0
	 *
	 * @throws MWException
	 * @return string
	 */
	public function getOriginalValue() {
		if ( $this->setCount == 0 ) {
			throw new MWException( 'No user input set to the parameter yet, so the original value does not exist' );
		}
		return $this->originalValue;
	}

	/**
	 * Returns all validation errors that occurred so far.
	 *
	 * @since 1.0
	 *
	 * @return ValidationError[]
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

	/**
	 * Gets if the parameter was set to it's default.
	 *
	 * @since 1.0
	 *
	 * @return boolean
	 */
	public function wasSetToDefault() {
		return $this->defaulted;
	}

	/**
	 * Returns false when there are no fatal errors or an ValidationError when one is found.
	 *
	 * @return mixed false or ValidationError
	 */
	public function hasFatalError() {
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
	 * @return boolean
	 */
	public function getName() {
		return $this->definition->getName();
	}

	/**
	 * Returns the parameter name aliases.
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function getAliases() {
		return $this->definition->getAliases();
	}

}