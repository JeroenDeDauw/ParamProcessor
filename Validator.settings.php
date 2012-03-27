<?php

/**
 * File defining the settings for the Validator extension.
 * More info can be found at https://www.mediawiki.org/wiki/Extension:Validator#Settings
 *
 * NOTICE:
 * Changing one of these settings can be done by assigning to $egValidatorSettings,
 * AFTER the inclusion of the extension itself.
 *
 * @since 0.5
 *
 * @file Validator.settings.php
 * @ingroup Validator
 *
 * @licence GNU GPL v3+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ValidatorSettings {

	protected function __construct(){}

	/**
	 * @since 0.5
	 * @var ValidatorSettings|null
	 */
	protected static $instance = null;

	/**
	 * @since 0.5
	 * @var array|null
	 */
	protected $settings = null;

	/**
	 * Returns an instance of ValidatorSettings.
	 *
	 * @since 0.5
	 *
	 * @return ValidatorSettings
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
	 * @since 0.5
	 *
	 * @return array
	 */
	protected function getDefaultSettings() {
		return array(
			'errorListMinSeverity' => 'minor',
			'errorActions' => array(
				ValidationError::SEVERITY_MINOR => ValidationError::ACTION_LOG,
				ValidationError::SEVERITY_LOW => ValidationError::ACTION_WARN,
				ValidationError::SEVERITY_NORMAL => ValidationError::ACTION_SHOW,
				ValidationError::SEVERITY_HIGH => ValidationError::ACTION_DEMAND,
			),
		);
	}

	/**
	 * Retruns an array with all settings after making sure they are
	 * initialized (ie set settings have been merged with the defaults).
	 * setting name (string) => setting value (mixed)
	 *
	 * @since 0.5
	 *
	 * @return array
	 */
	public function getSettings() {
		if ( is_null( $this->settings ) ) {
			$this->settings = array_merge(
				$this->getDefaultSettings(),
				$GLOBALS['egValidatorSettings']
			);
		}

		return $this->settings;
	}

	/**
	 * Gets the value of the specified setting.
	 *
	 * @since 0.5
	 *
	 * @param string $settingName
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function getSetting( $settingName ) {
		if ( !array_key_exists( $settingName, $this->settings ) ) {
			throw new MWException( 'Attempt to get non-existing setting "' . $settingName . '"' );
		}

		return $this->settings[$settingName];
	}

	/**
	 * Returns if a certain setting is set, and can therefor be obtained via getSetting.
	 *
	 * @since 0.5
	 *
	 * @param string $settingName
	 *
	 * @throws MWException
	 * @return mixed
	 */
	public function hasSetting( $settingName ) {
		return array_key_exists( $settingName, $this->settings );
	}

	/**
	 * Gets the value of the specified setting.
	 * Shortcut to ValidatorSettings::getSetting.
	 *
	 * @since 0.5
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
	 * @since 0.5
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
