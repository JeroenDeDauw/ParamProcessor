<?php

namespace ParamProcessor;

/**
 * @since 1.0
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ProcessingError {

	const SEVERITY_MINOR = 0;	// Minor error. ie a deprecation notice
	const SEVERITY_LOW = 1;		// Lower-then-normal severity. ie an unknown parameter
	const SEVERITY_NORMAL = 2;	// Normal severity. ie an invalid value provided
	const SEVERITY_HIGH = 3;	// Higher-then-normal severity. ie an invalid value for a significant parameter
	const SEVERITY_FATAL = 4;	// Fatal error. Either a missing or an invalid required parameter

	const ACTION_IGNORE = 0;	// Ignore the error
	const ACTION_LOG = 1;		// Log the error
	const ACTION_WARN = 2;		// Warn that there is an error
	const ACTION_SHOW = 3;		// Show the error
	const ACTION_DEMAND = 4;	// Show the error and don't render output

	public $message;
	public $severity;

	/**
	 * List of 'tags' for the error. This is mainly meant for indicating an error
	 * type, such as 'missing parameter' or 'invalid value', but allows for multiple
	 * such indications.
	 *
	 * @since 0.4
	 *
	 * @var string[]
	 */
	private $tags;

	/**
	 * Where the error occurred.
	 *
	 * @since 0.4
	 *
	 * @var string|bool
	 */
	public $element;

	/**
	 * @param string $message
	 * @param integer $severity
	 * @param string|bool $element
	 * @param string[] $tags
	 */
	public function __construct( string $message, int $severity = self::SEVERITY_NORMAL, $element = false, array $tags = [] ) {
		$this->message = $message;
		$this->severity = $severity;
		$this->element = $element;
		$this->tags = $tags;
	}

	/**
	 * Adds one or more tags.
	 *
	 * @since 0.4.1
	 *
	 * @param string|string[] $criteria
	 */
	public function addTags() {
		$args = func_get_args();
		$this->tags = array_merge( $this->tags, is_array( $args[0] ) ? $args[0] : $args );
	}

	public function getMessage(): string {
		return $this->message;
	}

	/**
	 * Returns the element this error occurred at, or 'unknown' when i's unknown.
	 */
	public function getElement(): string {
		return ( $this->element === false || $this->element === '' ) ? 'unknown' : $this->element;
	}

	/**
	 * Returns the severity of the error.
	 * @return integer Element of the self::SEVERITY_ enum
	 */
	public function getSeverity(): int {
		return $this->severity;
	}

	/**
	 * Returns if the severity is equal to or bigger then the provided one.
	 */
	public function hasSeverity( int $severity ): bool {
		return $this->severity >= $severity;
	}

	/**
	 * Returns if the error has a certain tag.
	 */
	public function hasTag( string $tag ): bool {
		return in_array( $tag, $this->tags );
	}

	/**
	 * @return string[]
	 */
	public function getTags(): array {
		return $this->tags;
	}

	/**
	 * Returns the action associated with the errors severity.
	 *
	 * @return integer Element of the self::ACTION_ enum
	 * @throws \Exception
	 */
	public function getAction(): int {
		// TODO: as option
		$errorActions = [
			self::SEVERITY_MINOR => self::ACTION_LOG,
			self::SEVERITY_LOW => self::ACTION_WARN,
			self::SEVERITY_NORMAL => self::ACTION_SHOW,
			self::SEVERITY_HIGH => self::ACTION_DEMAND,
		];

		if ( $this->severity === self::SEVERITY_FATAL ) {
			// This action should not be configurable, as lowering it would break in the Validator class.
			return self::ACTION_DEMAND;
		}
		elseif ( array_key_exists( $this->severity, $errorActions ) ) {
			return $errorActions[$this->severity];
		}
		else {
			throw new \Exception( "No action associated with error severity '$this->severity'" );
		}
	}

	/**
	 * Returns if the action associated with the severity is equal to or bigger then the provided one.
	 */
	public function hasAction( int $action ): bool {
		return $this->getAction() >= $action;
	}

	/**
	 * Returns if the error is fatal.
	 */
	public function isFatal(): bool {
		return $this->hasSeverity( self::SEVERITY_FATAL );
	}

	/**
	 * Returns if the error should be logged.
	 */
	public function shouldLog(): bool {
		return $this->hasAction( self::ACTION_LOG );
	}

	/**
	 * Returns if there should be a warning that errors are present.
	 */
	public function shouldWarn(): bool {
		return $this->hasAction( self::ACTION_WARN );
	}

	/**
	 * Returns if the error message should be shown.
	 */
	public function shouldShow(): bool {
		return $this->hasAction( self::ACTION_SHOW );
	}

	/**
	 * Returns if the error message should be shown, and the output not be rendered.
	 */
	public function shouldDemand(): bool {
		return $this->hasAction( self::ACTION_DEMAND );
	}

}
