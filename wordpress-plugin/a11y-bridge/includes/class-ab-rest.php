<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AB_REST {
	/**
	 * @var AB_Site_Audit
	 */
	private $site_audit;

	public function __construct( AB_Site_Audit $site_audit ) {
		$this->site_audit = $site_audit;

		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		register_rest_route(
			AB_Plugin::REST_NAMESPACE,
			'/status',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_status' ),
				'permission_callback' => array( $this, 'authorize_request' ),
			)
		);

		register_rest_route(
			AB_Plugin::REST_NAMESPACE,
			'/audit',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'run_audit' ),
				'permission_callback' => array( $this, 'authorize_request' ),
			)
		);
	}

	public function get_status() {
		$settings = AB_Plugin::get_settings();
		$audit    = AB_Plugin::get_last_audit();

		return rest_ensure_response(
			array(
				'success' => true,
				'data'    => array(
					'site_name'      => get_bloginfo( 'name' ),
					'site_url'       => home_url( '/' ),
					'onboarding'     => ! empty( $settings['onboarding_complete'] ),
					'service_mode'   => $settings['service_mode'],
					'statement_url'  => $settings['statement_url'],
					'last_audit'     => $audit,
					'plugin_version' => A11Y_BRIDGE_VERSION,
				),
			)
		);
	}

	public function run_audit() {
		$report = $this->site_audit->run();

		return rest_ensure_response(
			array(
				'success' => true,
				'data'    => $report,
			)
		);
	}

	public function authorize_request( WP_REST_Request $request ) {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		$settings       = AB_Plugin::get_settings();
		$expected_key   = isset( $settings['api_key'] ) ? (string) $settings['api_key'] : '';
		$provided_key   = (string) $request->get_header( 'x-a11y-bridge-key' );

		if ( '' !== $expected_key && hash_equals( $expected_key, $provided_key ) ) {
			return true;
		}

		return new WP_Error(
			'ab_forbidden',
			__( 'Invalid A11Y Bridge API key.', 'a11y-bridge' ),
			array( 'status' => 403 )
		);
	}
}
