<?php

namespace ParamProcessor;
use MWException;

/**
 * File defining the settings for the Validator extension.
 * More info can be found at https://www.mediawiki.org/wiki/Extension:Validator#Settings
 *
 * NOTICE:
 * Changing one of these settings can be done by assigning to $egValidatorSettings,
 * AFTER the inclusion of the extension itself.
 *
 * @since 1.0
 *
 * @file
 * @ingroup ParamProcessor
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
final class Settings {

	/**
	 * Protected constructor - force singleton usage.
	 * @since 1.0
	 */
	protected function __construct(){}

	/**
	 * @since 1.0
	 * @var Settings|null
	 */
	protected static $instance = null;

	/**
	 * @since 1.0
	 * @var array|null
	 */
	protected $settings = null;

	/**
	 * Returns an instance of ValidatorSettings.
	 *
	 * @since 1.0
	 *
	 * @return Settings
	 */
	public static function singleton() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Returns the default values for the settings.
	 * setting name (string) => setting value (mixed)
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	protected function getDefaultSettings() {
		return array(
			'errorListMinSeverity' => 'minor',
			'errorActions' => array(
				ProcessingError::SEVERITY_MINOR => ProcessingError::ACTION_LOG,
				ProcessingError::SEVERITY_LOW => ProcessingError::ACTION_WARN,
				ProcessingError::SEVERITY_NORMAL => ProcessingError::ACTION_SHOW,
				ProcessingError::SEVERITY_HIGH => ProcessingError::ACTION_DEMAND,
			),
		);
	}

	/**
	 * Builds the settings if needed.
	 * This includes merging the set settings over the default ones.
	 *
	 * @since 1.0
	 */
	protected function buildSettings() {
		if ( is_null( $this->settings ) ) {
			$this->settings = array_merge(
				self::getDefaultSettings(),
				$GLOBALS['egValidatorSettings']
			);
		}
	}

	/**
	 * Retruns an array with all settings after making sure they are
	 * initialized (ie set settings have been merged with the defaults).
	 * setting name (string) => setting value (mixed)
	 *
	 * @since 1.0
	 *
	 * @return array
	 */
	public function getSettings() {
		$this->buildSettings();
		return $this->settings;
	}

	/**
	 * Gets the value of the specified setting.
	 *
	 * @since 1.0
	 *
	 * @param string $settingName
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function getSetting( $settingName ) {
		$this->buildSettings();

		if ( !array_key_exists( $settingName, $this->settings ) ) {
			throw new MWException( 'Attempt to get non-existing setting "' . $settingName . '"' );
		}

		return $this->settings[$settingName];
	}

	/**
	 * Returns if a certain setting is set, and can therefor be obtained via getSetting.
	 *
	 * @since 1.0
	 *
	 * @param string $settingName
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function hasSetting( $settingName ) {
		$this->buildSettings();
		return array_key_exists( $settingName, $this->settings );
	}

	/**
	 * Gets the value of the specified setting.
	 * Shortcut to ValidatorSettings::getSetting.
	 *
	 * @since 1.0
	 *
	 * @param string $settingName
	 *
	 * @return mixed
	 */
	public static function get( $settingName ) {
		return self::singleton()->getSetting( $settingName );
	}

	/**
	 * Returns if a certain setting is set, and can therefor be obtained via getSetting.
	 * Shortcut to ValidatorSettings::hasSetting.
	 *
	 * @since 1.0
	 *
	 * @param string $settingName
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public static function has( $settingName ) {
		return self::singleton()->hasSetting( $settingName );
	}

}
