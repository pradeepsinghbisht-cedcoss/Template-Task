<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.2
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/includes
 * @author     support@botmywork.com
 */
class   WP_Chatbot_Builder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.2
	 * @var      WP_Chatbot_Builder_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.2
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.2
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.2
	 */
	public function __construct() {
		if ( defined( 'WP_CBB_VERSION' ) ) {
			$this->version = WP_CBB_VERSION;
		} else {
			$this->version = '1.0.2';
		}
		$this->plugin_name = 'wp-chatbot-builder';

		$this->load_dependencies();
		$this->set_locale();
		$this->includes();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Chatbot_Builder_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Chatbot_Builder_i18n. Defines internationalization functionality.
	 * - WP_Chatbot_Builder_Admin. Defines all hooks for the admin area.
	 * - WP_Chatbot_Builder_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.2
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-chatbot-builder-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-chatbot-builder-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-chatbot-builder-admin.php';

		// The class responsible for on-boarding steps for plugin.
		if ( is_dir( plugin_dir_path( dirname( __FILE__ ) ) . 'onboarding' ) && ! class_exists( 'WP_Chatbot_Builder_Onboarding_Steps' ) ) {

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-chatbot-builder-onboarding-steps.php';
		}

		if ( class_exists( 'WP_Chatbot_Builder_Onboarding_Steps' ) ) {
			$wp_cbb_onboard_steps = new WP_Chatbot_Builder_Onboarding_Steps();
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'package/rest-api/class-wp-chatbot-builder-rest-api.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-chatbot-builder-public.php';

		$this->loader = new WP_Chatbot_Builder_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Chatbot_Builder_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.2
	 */
	private function set_locale() {

		$plugin_i18n = new WP_Chatbot_Builder_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.2
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WP_Chatbot_Builder_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'activated_plugin', $plugin_admin, 'wp_cbb_activate_plugin' );
		$this->loader->add_filter( 'plugin_action_links_' . WP_CBB_PLUGIN_BASENAME, $plugin_admin, 'wp_cbb_plugin_action_links' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'wp_cbb_plugin_meta_links', 10, 2 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wp_cbb_options_page' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'wp_cbb_incomplete_setup' );
		// CONNECT BIT WITH FACEBOOK PAGE.
		$this->loader->add_action( 'wp_ajax_wp_cbb_connect_fb_page', $plugin_admin, 'wp_cbb_connect_fb_page' );
		$this->loader->add_action( 'wp_ajax_nopriv_wp_cbb_connect_fb_page', $plugin_admin, 'wp_cbb_connect_fb_page' );
		// SAVE PLUGIN ENABLE SETTING DATA.
		$this->loader->add_action( 'wp_ajax_wp_cbb_enable_plugin_save', $plugin_admin, 'wp_cbb_enable_plugin_save' );
		$this->loader->add_action( 'wp_ajax_nopriv_wp_cbb_enable_plugin_save', $plugin_admin, 'wp_cbb_enable_plugin_save' );
		// SAVE WELCOME MESSAGE.
		$this->loader->add_action( 'wp_ajax_wp_cbb_welcome_message_save', $plugin_admin, 'wp_cbb_welcome_message_save' );
		$this->loader->add_action( 'wp_ajax_nopriv_wp_cbb_welcome_message_save', $plugin_admin, 'wp_cbb_welcome_message_save' );
		// SAVE DEFAULT MESSAGE.
		$this->loader->add_action( 'wp_ajax_wp_cbb_default_message_save', $plugin_admin, 'wp_cbb_default_message_save' );
		$this->loader->add_action( 'wp_ajax_nopriv_wp_cbb_default_message_save', $plugin_admin, 'wp_cbb_default_message_save' );
		// Save Q/A DATA SET.
		$this->loader->add_action( 'wp_ajax_wp_cbb_qna_set_save', $plugin_admin, 'wp_cbb_qna_set_save' );
		$this->loader->add_action( 'wp_ajax_nopriv_wp_cbb_qna_set_save', $plugin_admin, 'wp_cbb_qna_set_save' );
		// REMOVE QUESTION AND ANSWER SET.
		$this->loader->add_action( 'wp_ajax_wp_cbb_remove_qna_set', $plugin_admin, 'wp_cbb_remove_qna_set' );
		$this->loader->add_action( 'wp_ajax_nopriv_wp_cbb_remove_qna_set', $plugin_admin, 'wp_cbb_remove_qna_set' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wp_cbb_export_lead_report' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.2
	 */
	private function define_public_hooks() {

		$plugin_public = new WP_Chatbot_Builder_Public( $this->get_plugin_name(), $this->get_version() );

		$wp_cbb_enable = get_option( 'wp_cbb_enable_plugin', 'off' );
		$this->api     = new BotMyWorkApi();
		if ( 'on' === $wp_cbb_enable ) :
			if ( null !== $this->api->wp_cbb_get_active_page_id() ) :
				// Add Facebook Messenger script.
				$this->loader->add_action( 'wp_footer', $plugin_public, 'wp_cbb_add_fb_msgr_script' );
				// Add Facebook Messenger button.
				$this->loader->add_action( 'wp_footer', $plugin_public, 'wp_cbb_add_fb_msgr_btn' );
			endif;
		endif;

	}

	/**
	 * Include Plugin Files.
	 *
	 * @since    1.0.2
	 */
	private function includes() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/contain/class-botmyworkapi.php';

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.2
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.2
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.2
	 * @return    WP_Chatbot_Builder_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.2
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
	/**
	 * Generate html components.
	 *
	 * @param  string $wcb_components    html to display.
	 * @since  1.0.2
	 */
	public function bmw_wcb_plug_generate_html( $wcb_components = array() ) {
		if ( is_array( $wcb_components ) && ! empty( $wcb_components ) ) {
			foreach ( $wcb_components as $wcb_component ) {
				if ( ! empty( $wcb_component['type'] ) && ! empty( $wcb_component['id'] ) ) {
					switch ( $wcb_component['type'] ) {

						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
						<div class="bmw-form-group bmw-wcb-<?php echo esc_attr( $wcb_component['type'] ); ?>">
							<div class="bmw-form-group__label">
								<label for="<?php echo esc_attr( $wcb_component['id'] ); ?>" class="bmw-form-label"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="bmw-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<?php if ( 'number' != $wcb_component['type'] ) { ?>
												<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $wcb_component['placeholder'] ) ? esc_attr( $wcb_component['placeholder'] ) : '' ); ?></span>
											<?php } ?>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input
									class="mdc-text-field__input <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>" 
									name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $wcb_component['id'] ); ?>"
									type="<?php echo esc_attr( $wcb_component['type'] ); ?>"
									value="<?php echo ( isset( $wcb_component['value'] ) ? esc_attr( $wcb_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $wcb_component['placeholder'] ) ? esc_attr( $wcb_component['placeholder'] ) : '' ); ?>"
									>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent bmw-helper-text" id="" aria-hidden="true"><?php echo ( isset( $wcb_component['description'] ) ? esc_attr( $wcb_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'password':
							?>
						<div class="bmw-form-group">
							<div class="bmw-form-group__label">
								<label for="<?php echo esc_attr( $wcb_component['id'] ); ?>" class="bmw-form-label"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="bmw-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input 
									class="mdc-text-field__input <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?> bmw-form__password" 
									name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $wcb_component['id'] ); ?>"
									type="<?php echo esc_attr( $wcb_component['type'] ); ?>"
									value="<?php echo ( isset( $wcb_component['value'] ) ? esc_attr( $wcb_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $wcb_component['placeholder'] ) ? esc_attr( $wcb_component['placeholder'] ) : '' ); ?>"
									>
									<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing bmw-password-hidden" tabindex="0" role="button">visibility</i>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent bmw-helper-text" id="" aria-hidden="true"><?php echo ( isset( $wcb_component['description'] ) ? esc_attr( $wcb_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'textarea':
							?>
						<div class="bmw-form-group">
							<div class="bmw-form-group__label">
								<label class="bmw-form-label" for="<?php echo esc_attr( $wcb_component['id'] ); ?>"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="bmw-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"  	for="text-field-hero-input">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label"><?php echo ( isset( $wcb_component['placeholder'] ) ? esc_attr( $wcb_component['placeholder'] ) : '' ); ?></span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<span class="mdc-text-field__resizer">
										<textarea class="mdc-text-field__input <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>" id="<?php echo esc_attr( $wcb_component['id'] ); ?>" placeholder="<?php echo ( isset( $wcb_component['placeholder'] ) ? esc_attr( $wcb_component['placeholder'] ) : '' ); ?>"><?php echo ( isset( $wcb_component['value'] ) ? esc_textarea( $wcb_component['value'] ) : '' ); // WPCS: XSS ok. ?></textarea>
									</span>
								</label>

							</div>
						</div>

							<?php
							break;

						case 'select':
						case 'multiselect':
							?>
						<div class="bmw-form-group">
							<div class="bmw-form-group__label">
								<label class="bmw-form-label" for="<?php echo esc_attr( $wcb_component['id'] ); ?>"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="bmw-form-group__control">
								<div class="bmw-form-select">
									<select id="<?php echo esc_attr( $wcb_component['id'] ); ?>" name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : '' ); ?><?php echo ( 'multiselect' === $wcb_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $wcb_component['id'] ); ?>" class="mdl-textfield__input <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>" <?php echo 'multiselect' === $wcb_component['type'] ? 'multiple="multiple"' : ''; ?> >
										<?php
										foreach ( $wcb_component['options'] as $wcb_key => $wcb_val ) {
											?>
											<option value="<?php echo esc_attr( $wcb_key ); ?>"
												<?php
												if ( is_array( $wcb_component['value'] ) ) {
													selected( in_array( (string) $wcb_key, $wcb_component['value'], true ), true );
												} else {
													selected( $wcb_component['value'], (string) $wcb_key );
												}
												?>
												>
												<?php echo esc_html( $wcb_val ); ?>
											</option>
											<?php
										}
										?>
									</select>
									<label class="mdl-textfield__label" for="octane"><?php echo esc_html( $wcb_component['description'] ); ?><?php echo ( isset( $wcb_component['description'] ) ? esc_attr( $wcb_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>

							<?php
							break;

						case 'checkbox':
							?>
						<div class="bmw-form-group">
							<div class="bmw-form-group__label">
								<label for="<?php echo esc_attr( $wcb_component['id'] ); ?>" class="bmw-form-label"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="bmw-form-group__control bmw-pl-4">
								<div class="mdc-form-field">
									<div class="mdc-checkbox">
										<input 
										name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $wcb_component['id'] ); ?>"
										type="checkbox"
										class="mdc-checkbox__native-control <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>"
										value="<?php echo ( isset( $wcb_component['value'] ) ? esc_attr( $wcb_component['value'] ) : '' ); ?>"
										<?php checked( $wcb_component['value'], '1' ); ?>
										/>
										<div class="mdc-checkbox__background">
											<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
												<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
											</svg>
											<div class="mdc-checkbox__mixedmark"></div>
										</div>
										<div class="mdc-checkbox__ripple"></div>
									</div>
									<label for="checkbox-1"><?php echo ( isset( $wcb_component['description'] ) ? esc_attr( $wcb_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio':
							?>
						<div class="bmw-form-group">
							<div class="bmw-form-group__label">
								<label for="<?php echo esc_attr( $wcb_component['id'] ); ?>" class="bmw-form-label"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="bmw-form-group__control bmw-pl-4">
								<div class="bmw-flex-col">
									<?php
									foreach ( $wcb_component['options'] as $wcb_radio_key => $wcb_radio_val ) {
										?>
										<div class="mdc-form-field">
											<div class="mdc-radio">
												<input
												name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
												value="<?php echo esc_attr( $wcb_radio_key ); ?>"
												type="radio"
												class="mdc-radio__native-control <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>"
												<?php checked( $wcb_radio_key, $wcb_component['value'] ); ?>
												>
												<div class="mdc-radio__background">
													<div class="mdc-radio__outer-circle"></div>
													<div class="mdc-radio__inner-circle"></div>
												</div>
												<div class="mdc-radio__ripple"></div>
											</div>
											<label for="radio-1"><?php echo esc_html( $wcb_radio_val ); ?></label>
										</div>	
										<?php
									}
									?>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio-switch':
							?>

						<div class="bmw-form-group">
							<div class="bmw-form-group__label">
								<label for="" class="bmw-form-label"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
							</div>
							<div class="bmw-form-group__control">
								<div>
									<div class="mdc-switch">
										<div class="mdc-switch__track"></div>
										<div class="mdc-switch__thumb-underlay">
											<div class="mdc-switch__thumb"></div>
											<input name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>" type="checkbox" id="<?php echo esc_html( $wcb_component['id'] ); ?>" value="on" class="mdc-switch__native-control <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>" role="switch" aria-checked="<?php if ( 'on' == $wcb_component['value'] ) echo 'true'; else echo 'false'; ?>"
											<?php checked( $wcb_component['value'], 'on' ); ?>
											>
										</div>
									</div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'button':
							?>
						<div class="bmw-form-group">
							<div class="bmw-form-group__label"></div>
							<div class="bmw-form-group__control">
								<button class="mdc-button mdc-button--raised" name= "<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $wcb_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
									<span class="mdc-button__label <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>"><?php echo ( isset( $wcb_component['button_text'] ) ? esc_html( $wcb_component['button_text'] ) : '' ); ?></span>
								</button>
							</div>
						</div>

							<?php
							break;

						case 'multi':
							?>
							<div class="bmw-form-group bmw-isfw-<?php echo esc_attr( $wcb_component['type'] ); ?>">
								<div class="bmw-form-group__label">
									<label for="<?php echo esc_attr( $wcb_component['id'] ); ?>" class="bmw-form-label"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
									</div>
									<div class="bmw-form-group__control">
									<?php
									foreach ( $wcb_component['value'] as $component ) {
										?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ( 'number' != $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $wcb_component['placeholder'] ) ? esc_attr( $wcb_component['placeholder'] ) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<input 
												class="mdc-text-field__input <?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( $component['type'] ); ?>"
												value="<?php echo ( isset( $wcb_component['value'] ) ? esc_attr( $wcb_component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $wcb_component['placeholder'] ) ? esc_attr( $wcb_component['placeholder'] ) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'max=10 min=0' : '' ); ?>
												>
											</label>
								<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent bmw-helper-text" id="" aria-hidden="true"><?php echo ( isset( $wcb_component['description'] ) ? esc_attr( $wcb_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;
						case 'color':
						case 'date':
						case 'file':
							?>
							<div class="bmw-form-group bmw-isfw-<?php echo esc_attr( $wcb_component['type'] ); ?>">
								<div class="bmw-form-group__label">
									<label for="<?php echo esc_attr( $wcb_component['id'] ); ?>" class="bmw-form-label"><?php echo ( isset( $wcb_component['title'] ) ? esc_html( $wcb_component['title'] ) : '' ); // WPCS: XSS ok. ?></label>
								</div>
								<div class="bmw-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined">
										<input 
										class="<?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>" 
										name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $wcb_component['id'] ); ?>"
										type="<?php echo esc_attr( $wcb_component['type'] ); ?>"
										value="<?php echo ( isset( $wcb_component['value'] ) ? esc_attr( $wcb_component['value'] ) : '' ); ?>"
										<?php echo esc_html( ( 'date' === $wcb_component['type'] ) ? 'max='. date( 'Y-m-d', strtotime( date( "Y-m-d", mktime() ) . " + 365 day" ) ) .' ' . 'min=' . date( 'Y-m-d' ) . '' : '' ); ?>
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent bmw-helper-text" id="" aria-hidden="true"><?php echo ( isset( $wcb_component['description'] ) ? esc_attr( $wcb_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'submit':
							?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo ( isset( $wcb_component['name'] ) ? esc_html( $wcb_component['name'] ) : esc_html( $wcb_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $wcb_component['id'] ); ?>"
								class="<?php echo ( isset( $wcb_component['class'] ) ? esc_attr( $wcb_component['class'] ) : '' ); ?>"
								value="<?php echo esc_attr( $wcb_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
							<?php
							break;

						default:
							break;
					}
				}
			}
		}
	}
}
