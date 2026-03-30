<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AB_Frontend {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_body_open', array( $this, 'render_skip_link' ) );
		add_action( 'wp_footer', array( $this, 'render_hosted_widget_script' ), 99 );
		add_filter( 'language_attributes', array( $this, 'normalize_language_attributes' ) );
	}

	public function enqueue_assets() {
		$settings = AB_Plugin::get_settings();

		if ( empty( $settings['auto_skip_link'] ) && empty( $settings['enhance_focus_styles'] ) ) {
			return;
		}

		wp_enqueue_style(
			'ab-frontend',
			A11Y_BRIDGE_PLUGIN_URL . 'assets/frontend.css',
			array(),
			A11Y_BRIDGE_VERSION
		);

		wp_enqueue_script(
			'ab-frontend',
			A11Y_BRIDGE_PLUGIN_URL . 'assets/frontend.js',
			array(),
			A11Y_BRIDGE_VERSION,
			true
		);
	}

	public function render_skip_link() {
		$settings = AB_Plugin::get_settings();

		if ( empty( $settings['auto_skip_link'] ) ) {
			return;
		}
		?>
		<a class="ab-skip-link" href="#main-content"><?php esc_html_e( 'דלג לתוכן הראשי', 'a11y-bridge' ); ?></a>
		<?php
	}

	public function normalize_language_attributes( $output ) {
		$settings = AB_Plugin::get_settings();

		if ( empty( $settings['normalize_lang'] ) || false !== strpos( $output, ' lang=' ) ) {
			return $output;
		}

		$locale = get_locale();
		$lang   = strtolower( str_replace( '_', '-', $locale ) );

		return trim( $output . ' lang="' . esc_attr( $lang ) . '"' );
	}

	public function render_hosted_widget_script() {
		$settings = AB_Plugin::get_settings();

		if ( empty( $settings['auto_embed_widget'] ) ) {
			return;
		}

		$script_url = ! empty( $settings['widget_script_url'] ) ? esc_url( $settings['widget_script_url'] ) : '';
		$site_key   = ! empty( $settings['site_key'] ) ? esc_attr( $settings['site_key'] ) : '';

		if ( '' === $script_url || '' === $site_key ) {
			return;
		}
		?>
		<script async src="<?php echo $script_url; ?>" data-a11y-bridge="<?php echo $site_key; ?>"></script>
		<?php
	}
}
