<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AB_Admin {
	/**
	 * @var AB_Site_Audit
	 */
	private $site_audit;

	public function __construct( AB_Site_Audit $site_audit ) {
		$this->site_audit = $site_audit;

		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_ab_save_settings', array( $this, 'handle_save_settings' ) );
		add_action( 'admin_post_ab_run_audit', array( $this, 'handle_run_audit' ) );
	}

	public function register_menu() {
		add_menu_page(
			'A11Y Bridge',
			'A11Y Bridge',
			'manage_options',
			AB_Plugin::MENU_SLUG,
			array( $this, 'render_page' ),
			'dashicons-universal-access-alt',
			58
		);
	}

	public function enqueue_assets( $hook ) {
		if ( false === strpos( (string) $hook, AB_Plugin::MENU_SLUG ) ) {
			return;
		}

		wp_enqueue_style(
			'ab-admin',
			A11Y_BRIDGE_PLUGIN_URL . 'assets/admin.css',
			array(),
			A11Y_BRIDGE_VERSION
		);

		wp_enqueue_script(
			'ab-admin',
			A11Y_BRIDGE_PLUGIN_URL . 'assets/admin.js',
			array(),
			A11Y_BRIDGE_VERSION,
			true
		);
	}

	public function handle_save_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'a11y-bridge' ) );
		}

		check_admin_referer( AB_Plugin::NONCE_ACTION );

		$existing = AB_Plugin::get_settings();
		$api_key  = sanitize_text_field( $this->post_value( 'api_key' ) );

		$updated = array(
			'company_name'            => sanitize_text_field( $this->post_value( 'company_name' ) ),
			'contact_email'           => sanitize_email( $this->post_value( 'contact_email' ) ),
			'statement_url'           => esc_url_raw( $this->post_value( 'statement_url' ) ),
			'remote_endpoint'         => esc_url_raw( $this->post_value( 'remote_endpoint' ) ),
			'widget_script_url'       => esc_url_raw( $this->post_value( 'widget_script_url' ) ),
			'site_key'                => sanitize_text_field( $this->post_value( 'site_key' ) ),
			'api_key'                 => '' !== $api_key ? $api_key : $existing['api_key'],
			'service_mode'            => $this->sanitize_choice( $this->post_value( 'service_mode' ), array( 'audit_only', 'audit_and_fix', 'managed_service' ), 'audit_and_fix' ),
			'auto_embed_widget'       => $this->checkbox_value( 'auto_embed_widget' ),
			'auto_skip_link'          => $this->checkbox_value( 'auto_skip_link' ),
			'enhance_focus_styles'    => $this->checkbox_value( 'enhance_focus_styles' ),
			'normalize_lang'          => $this->checkbox_value( 'normalize_lang' ),
			'onboarding_complete'     => 1,
			'onboarding_completed_at' => ! empty( $existing['onboarding_completed_at'] ) ? $existing['onboarding_completed_at'] : current_time( 'mysql' ),
		);

		AB_Plugin::update_settings( $updated );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'      => AB_Plugin::MENU_SLUG,
					'ab_notice' => 'ההגדרות נשמרו.',
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	public function handle_run_audit() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized.', 'a11y-bridge' ) );
		}

		check_admin_referer( AB_Plugin::NONCE_ACTION );
		$this->site_audit->run();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'      => AB_Plugin::MENU_SLUG,
					'ab_notice' => 'ה-audit הושלם.',
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings     = AB_Plugin::get_settings();
		$last_audit   = AB_Plugin::get_last_audit();
		$notice       = isset( $_GET['ab_notice'] ) ? sanitize_text_field( wp_unslash( $_GET['ab_notice'] ) ) : '';
		$status_label = ! empty( $settings['onboarding_complete'] ) ? 'Connected' : 'Needs setup';
		$status_class = ! empty( $settings['onboarding_complete'] ) ? 'is-ready' : 'is-pending';
		$site_name    = get_bloginfo( 'name' );
		$site_url     = home_url( '/' );
		$audit_score  = isset( $last_audit['score'] ) ? (int) $last_audit['score'] : 0;
		$rest_status  = rest_url( AB_Plugin::REST_NAMESPACE . '/status' );
		$embed_code   = $this->build_embed_code( $settings );
		?>
		<div class="wrap ab-admin-wrap">
			<div class="ab-hero">
				<div class="ab-hero-copy">
					<div class="ab-kicker">WordPress connector</div>
					<h1>A11Y Bridge</h1>
					<p>מסך חיבור לאתרי WordPress שמציע audit ראשוני, חיבור ל-SaaS ושיפורי frontend בטוחים. המטרה כאן היא לפתוח workflow אמיתי של נגישות, לא למכור overlay קסם.</p>
				</div>
				<div class="ab-hero-meta">
					<span class="ab-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_label ); ?></span>
					<p><?php echo ! empty( $settings['onboarding_completed_at'] ) ? esc_html( 'Connected מאז ' . $settings['onboarding_completed_at'] ) : esc_html( 'ה-onboarding עדיין לא הושלם.' ); ?></p>
				</div>
			</div>

			<?php if ( $notice ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
			<?php endif; ?>

			<div class="ab-grid">
				<section class="ab-card">
					<div class="ab-kicker">Connection</div>
					<h2>חיבור האתר למערכת</h2>
					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="ab-form">
						<input type="hidden" name="action" value="ab_save_settings" />
						<?php wp_nonce_field( AB_Plugin::NONCE_ACTION ); ?>

						<label for="ab-company-name">Company name</label>
						<input id="ab-company-name" name="company_name" type="text" value="<?php echo esc_attr( $settings['company_name'] ); ?>" />

						<label for="ab-contact-email">Contact email</label>
						<input id="ab-contact-email" name="contact_email" type="email" value="<?php echo esc_attr( $settings['contact_email'] ); ?>" />

						<label for="ab-statement-url">Accessibility statement URL</label>
						<input id="ab-statement-url" name="statement_url" type="url" placeholder="https://example.com/accessibility-statement" value="<?php echo esc_attr( $settings['statement_url'] ); ?>" />

						<label for="ab-remote-endpoint">Remote endpoint</label>
						<input id="ab-remote-endpoint" name="remote_endpoint" type="url" placeholder="https://app.a11ybridge.com" value="<?php echo esc_attr( $settings['remote_endpoint'] ); ?>" />

						<label for="ab-widget-script-url">Widget script URL</label>
						<input id="ab-widget-script-url" name="widget_script_url" type="url" placeholder="https://app.a11ybridge.com/widget.js" value="<?php echo esc_attr( $settings['widget_script_url'] ); ?>" />

						<label for="ab-site-key">Site key</label>
						<input id="ab-site-key" name="site_key" type="text" placeholder="LikFgwDvPyha" value="<?php echo esc_attr( $settings['site_key'] ); ?>" />

						<label for="ab-api-key">Shared API key</label>
						<input id="ab-api-key" name="api_key" type="text" value="<?php echo esc_attr( $settings['api_key'] ); ?>" />

						<label for="ab-service-mode">Service mode</label>
						<select id="ab-service-mode" name="service_mode">
							<?php foreach ( $this->get_service_modes() as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $settings['service_mode'], $value ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>

						<div class="ab-checkboxes">
							<label><input type="checkbox" name="auto_embed_widget" value="1" <?php checked( ! empty( $settings['auto_embed_widget'] ) ); ?> /> Auto-embed hosted widget</label>
							<label><input type="checkbox" name="auto_skip_link" value="1" <?php checked( ! empty( $settings['auto_skip_link'] ) ); ?> /> Enable safe skip link</label>
							<label><input type="checkbox" name="enhance_focus_styles" value="1" <?php checked( ! empty( $settings['enhance_focus_styles'] ) ); ?> /> Enhance focus styles</label>
							<label><input type="checkbox" name="normalize_lang" value="1" <?php checked( ! empty( $settings['normalize_lang'] ) ); ?> /> Normalize lang attribute</label>
						</div>

						<button type="submit" class="button button-primary button-hero">Save connection</button>
					</form>
				</section>

				<section class="ab-card ab-dark-card">
					<div class="ab-kicker">Platform binding</div>
					<h2><?php echo esc_html( $site_name ); ?></h2>
					<ul class="ab-meta-list">
						<li><strong>Homepage:</strong> <?php echo esc_html( $site_url ); ?></li>
						<li><strong>REST status:</strong> <code><?php echo esc_html( $rest_status ); ?></code></li>
						<li><strong>Audit score:</strong> <?php echo $audit_score ? esc_html( (string) $audit_score ) : esc_html( 'Not scanned yet' ); ?></li>
						<li><strong>Widget script:</strong> <?php echo ! empty( $settings['widget_script_url'] ) ? esc_html( $settings['widget_script_url'] ) : esc_html( 'Not connected yet' ); ?></li>
						<li><strong>Site key:</strong> <?php echo ! empty( $settings['site_key'] ) ? esc_html( $settings['site_key'] ) : esc_html( 'Missing' ); ?></li>
					</ul>
					<p>כאן מחברים את אתר ה-WordPress לחשבון שנפתח בפלטפורמה שלך. לאחר שמכניסים `widget.js` ו-`site key`, התוסף יכול להטמיע את ה-widget אוטומטית באתר בלי להדביק ידנית קוד בקבצי התבנית.</p>
					<div class="ab-button-row">
						<button type="button" class="button button-secondary ab-copy-button" data-copy="<?php echo esc_attr( $rest_status ); ?>">Copy REST endpoint</button>
						<?php if ( $embed_code ) : ?>
							<button type="button" class="button button-secondary ab-copy-button" data-copy="<?php echo esc_attr( $embed_code ); ?>">Copy embed code</button>
						<?php endif; ?>
					</div>
				</section>
			</div>

			<div class="ab-grid">
				<section class="ab-card">
					<div class="ab-section-head">
						<div>
							<div class="ab-kicker">Local audit</div>
							<h2>בדיקה ראשונית מתוך WordPress</h2>
						</div>
						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
							<input type="hidden" name="action" value="ab_run_audit" />
							<?php wp_nonce_field( AB_Plugin::NONCE_ACTION ); ?>
							<button type="submit" class="button button-primary">Run audit</button>
						</form>
					</div>

					<?php if ( ! empty( $last_audit ) ) : ?>
						<div class="ab-score-row">
							<div class="ab-score-orb"><?php echo esc_html( (string) $last_audit['score'] ); ?></div>
							<div>
								<p><strong><?php echo esc_html( $last_audit['title'] ); ?></strong></p>
								<p><?php echo esc_html( $last_audit['summary'] ); ?></p>
								<p class="description">Generated at <?php echo esc_html( $last_audit['generated_at'] ); ?></p>
							</div>
						</div>

						<div class="ab-chip-grid">
							<?php if ( ! empty( $last_audit['signals'] ) && is_array( $last_audit['signals'] ) ) : ?>
								<?php foreach ( $last_audit['signals'] as $signal ) : ?>
									<div class="ab-chip">
										<span><?php echo esc_html( $signal['label'] ); ?></span>
										<strong><?php echo esc_html( $signal['value'] ); ?></strong>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>

						<div class="ab-columns">
							<div>
								<h3>Highlights</h3>
								<ul>
									<?php foreach ( $last_audit['highlights'] as $highlight ) : ?>
										<li><?php echo esc_html( $highlight ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
							<div>
								<h3>Auto-fix opportunities</h3>
								<ul>
									<?php foreach ( $last_audit['autofixes'] as $autofix ) : ?>
										<li><?php echo esc_html( $autofix ); ?></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>

						<h3>Issues</h3>
						<div class="ab-issues">
							<?php foreach ( $last_audit['issues'] as $issue ) : ?>
								<article class="ab-issue ab-<?php echo esc_attr( $issue['severity'] ); ?>">
									<div class="ab-issue-head">
										<span class="ab-pill"><?php echo esc_html( strtoupper( $issue['severity'] ) ); ?></span>
										<strong><?php echo esc_html( $issue['title'] ); ?></strong>
										<span class="ab-count"><?php echo esc_html( (string) $issue['count'] ); ?></span>
									</div>
									<p><?php echo esc_html( $issue['description'] ); ?></p>
									<p><strong>Impact:</strong> <?php echo esc_html( $issue['impact'] ); ?></p>
									<p><strong>Next action:</strong> <?php echo esc_html( $issue['action'] ); ?></p>
									<p class="description">WCAG: <?php echo esc_html( implode( ', ', $issue['wcag'] ) ); ?></p>
								</article>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<p>עוד לא רצתה בדיקה. לחץ על <strong>Run audit</strong> כדי לקבל snapshot ראשון של האתר מתוך וורדפרס.</p>
					<?php endif; ?>
				</section>

				<section class="ab-card">
					<div class="ab-kicker">Hosted embed</div>
					<h2>קוד הטמעה מהפלטפורמה</h2>
					<?php if ( $embed_code ) : ?>
						<p>אם תבחר לא להטמיע אוטומטית דרך התוסף, אפשר להשתמש באותו snippet ידני. הקוד נשאר קבוע, וההגדרות מתעדכנות מהפלטפורמה שלך לפי ה-site key.</p>
						<pre class="ab-code-block"><code><?php echo esc_html( $embed_code ); ?></code></pre>
					<?php else : ?>
						<p>כדי לייצר snippet כאן, צריך להזין את `Widget script URL` ואת `Site key` שקיבלת מה-platform dashboard.</p>
					<?php endif; ?>
				</section>

				<section class="ab-card">
					<div class="ab-kicker">Operating model</div>
					<h2>מה מוכרים ללקוח דרך התוסף</h2>
					<ul>
						<li>פתיחת חשבון בפלטפורמה וקבלת site key אישי לכל אתר.</li>
						<li>קוד הטמעה קבוע שמושך config עדכני מהפלטפורמה שלך.</li>
						<li>Audit פתיחה מתוך WordPress להפקת backlog לפי חומרה.</li>
						<li>שכבת frontend זהירה: skip link, focus ring, וחיזוק lang.</li>
					</ul>

					<div class="ab-kicker ab-kicker-spaced">Suggested pitch</div>
					<p>“אנחנו לא מוכרים toolbar שמבטיח הכול. אנחנו מחברים את האתר שלכם למערכת audit ותיקון מדורג, עם דוח, תיקונים בטוחים ובדיקה אנושית.”</p>
				</section>
			</div>
		</div>
		<?php
	}

	private function post_value( $key ) {
		return isset( $_POST[ $key ] ) ? wp_unslash( (string) $_POST[ $key ] ) : '';
	}

	private function checkbox_value( $key ) {
		return isset( $_POST[ $key ] ) ? 1 : 0;
	}

	private function sanitize_choice( $value, array $allowed, $fallback ) {
		return in_array( $value, $allowed, true ) ? $value : $fallback;
	}

	private function get_service_modes() {
		return array(
			'audit_only'      => 'Audit only',
			'audit_and_fix'   => 'Audit + safe fixes',
			'managed_service' => 'Managed accessibility service',
		);
	}

	private function build_embed_code( $settings ) {
		$script_url = ! empty( $settings['widget_script_url'] ) ? esc_url_raw( (string) $settings['widget_script_url'] ) : '';
		$site_key   = ! empty( $settings['site_key'] ) ? sanitize_text_field( (string) $settings['site_key'] ) : '';

		if ( '' === $script_url || '' === $site_key ) {
			return '';
		}

		return sprintf(
			'<script async src="%1$s" data-a11y-bridge="%2$s"></script>',
			$script_url,
			$site_key
		);
	}
}
