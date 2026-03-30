<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AB_Plugin {
	const OPTION_SETTINGS   = 'ab_settings';
	const OPTION_AUDIT      = 'ab_last_audit';
	const OPTION_VERSION    = 'ab_version';
	const MENU_SLUG         = 'a11y-bridge';
	const NONCE_ACTION      = 'ab_admin_action';
	const REST_NAMESPACE    = 'a11y-bridge/v1';

	/**
	 * @var AB_Plugin|null
	 */
	private static $instance = null;

	/**
	 * @var AB_Site_Audit
	 */
	private $site_audit;

	/**
	 * @var AB_Admin
	 */
	private $admin;

	/**
	 * @var AB_Frontend
	 */
	private $frontend;

	/**
	 * @var AB_REST
	 */
	private $rest;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function activate() {
		self::instance()->maybe_upgrade();

		if ( false === get_option( self::OPTION_AUDIT, false ) ) {
			update_option( self::OPTION_AUDIT, array(), false );
		}
	}

	public static function get_default_settings() {
		return array(
			'company_name'           => get_bloginfo( 'name' ),
			'contact_email'          => get_option( 'admin_email' ),
			'statement_url'          => '',
			'remote_endpoint'        => '',
			'widget_script_url'      => '',
			'site_key'               => '',
			'api_key'                => wp_generate_password( 24, false, false ),
			'service_mode'           => 'audit_and_fix',
			'auto_embed_widget'      => 0,
			'auto_skip_link'         => 1,
			'enhance_focus_styles'   => 1,
			'normalize_lang'         => 1,
			'onboarding_complete'    => 0,
			'onboarding_completed_at' => '',
		);
	}

	public static function get_settings() {
		$saved = get_option( self::OPTION_SETTINGS, array() );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return wp_parse_args( $saved, self::get_default_settings() );
	}

	public static function update_settings( array $settings ) {
		$merged = wp_parse_args( $settings, self::get_default_settings() );
		update_option( self::OPTION_SETTINGS, $merged, false );

		return $merged;
	}

	public static function get_last_audit() {
		$audit = get_option( self::OPTION_AUDIT, array() );

		return is_array( $audit ) ? $audit : array();
	}

	public static function update_last_audit( array $audit ) {
		update_option( self::OPTION_AUDIT, $audit, false );
	}

	public function maybe_upgrade() {
		$installed_version = get_option( self::OPTION_VERSION );

		if ( A11Y_BRIDGE_VERSION === $installed_version ) {
			return;
		}

		if ( false === get_option( self::OPTION_SETTINGS, false ) ) {
			add_option( self::OPTION_SETTINGS, self::get_default_settings() );
		} else {
			self::update_settings( self::get_settings() );
		}

		if ( false === get_option( self::OPTION_AUDIT, false ) ) {
			add_option( self::OPTION_AUDIT, array(), false );
		}

		update_option( self::OPTION_VERSION, A11Y_BRIDGE_VERSION, false );
	}

	private function __construct() {
		$this->site_audit = new AB_Site_Audit();
		$this->admin      = new AB_Admin( $this->site_audit );
		$this->frontend   = new AB_Frontend();
		$this->rest       = new AB_REST( $this->site_audit );

		add_action( 'init', array( $this, 'maybe_upgrade' ) );
	}
}
