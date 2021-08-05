<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/admin
 * @author     support@botmywork.com
 */
class WP_Chatbot_Builder_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.2
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.2
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The api for this plugin.
	 *
	 * @since    1.0.2
	 * @var      string    $api    The api for this plugin.
	 */
	private $api;

	/**
	 * Stores Authentication Token
	 *
	 * @since    1.0.2
	 * @var      string    $token    Stores Authentication Token
	 */
	private $token;


	/**
	 * Stores Connected Facebook PageId
	 *
	 * @since    1.0.2
	 * @var      string    $token    Stores Connected Facebook PageId
	 */
	private $fb_page_id;

	/**
	 * Stores Active Facebook Page Info
	 *
	 * @since    1.0.2
	 * @var      array    $internal    Active Facebook Page Info
	 */
	private $internal;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.2
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->api         = new BotMyWorkApi();
		$this->token       = $this->api->wp_cbb_connect_bot_my_work();
		$this->fb_page_id  = $this->api->wp_cbb_get_active_page_id();
		$this->internal    = $this->api->wp_cbb_get_active_page();
	}

	/**
	 * Return BotMyWorkApi object.
	 *
	 * @since    1.0.2
	 */
	private function get_api() {
		return $this->api;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.2
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Chatbot_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Chatbot_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			if ( ( isset( $screen->id ) ) && ( 'plugins' === $screen->id || 'toplevel_page_wp-chatbot-builder' === $screen->id ) ) {
				wp_enqueue_style( 'wp-cbb-select2-style', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-cbb-admin.css', array(), $this->version, 'all' );
				wp_enqueue_style( 'font_awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', $this->version );
			}
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.2
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Chatbot_Builder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Chatbot_Builder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			if ( ( isset( $screen->id ) ) && ( 'plugins' === $screen->id || 'toplevel_page_wp-chatbot-builder' === $screen->id ) ) {
				wp_enqueue_script( 'wp-cbb-select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, false );
				$wp_cbb = array(
					'ajaxurl'      => admin_url( 'admin-ajax.php' ),
					'wp_cbb_nonce' => wp_create_nonce( 'wp-cbb-verify-nonce' ),
					'wp_cbb_url'   => WP_CBB_URL,
				);
				wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-cbb-admin.js', array( 'jquery', 'wp-color-picker', 'wp-cbb-select2' ), $this->version, false );
				wp_localize_script( $this->plugin_name, 'wp_cbb_handle', $wp_cbb );
				wp_enqueue_script( $this->plugin_name );
			}
		}
	}

	/**
	 * After plugin activate this function will call
	 *
	 * Redirect to setting page
	 *
	 * @since 1.0.2
	 * @name wp_cbb_activate_plugin
	 * @param string $plugin plugin name.
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_activate_plugin( $plugin ) {
		if ( plugin_basename( WP_CBB_PLUGIN_FILE ) === $plugin ) {
			wp_redirect( esc_url_raw( admin_url( 'admin.php?page=wp-chatbot-builder' ) ) );
			exit;
		}
	}

	/**
	 * Add settings page links in plugins page - at plugin
	 *
	 * Redirect to setting page
	 *
	 * @since 1.0.2
	 * @name wp_cbb_plugin_action_links
	 * @param array $links plugin link.
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_plugin_action_links( $links ) {
		$new_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wp-chatbot-builder' ) . '">' . __( 'Settings', 'wp-chatbot-builder' ) . '</a>',
		);

		return array_merge( $new_links, $links );
	}

	/**
	 * Create menu bar on admin panel.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_options_page
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_options_page() {
		$notification = '!';
		add_menu_page(
			'WP-Chatbot Builder Setting Page',
			! $this->fb_page_id || ! $this->token || ! $this->internal ? sprintf( 'WP-Chatbot<br>Builder <span class="awaiting-mod">%s</span>', $notification ) : '<span data-tab="tab-1">WP-Chatbot<br>Builder</span>',
			'manage_options',
			'wp-chatbot-builder',
			array( $this, 'wp_cbb_settings_page' ),
			plugins_url( 'wp-chatbot-builder/assets/images/cbb_mini_logo.png' )
		);
	}

	/**
	 * Display Pages based on settings.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_settings_page
	 * @return void
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		include_once WP_CBB_DIRPATH . '/admin/partials/wp-cbb-setting-options.php';
	}

	/**
	 * Display Pages based on settings.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_incomplete_setup
	 * @return void
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_incomplete_setup() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			if ( ( isset( $screen->id ) ) && ( 'plugins' === $screen->id || 'toplevel_page_wp-chatbot-builder' === $screen->id ) ) {
				include_once WP_CBB_DIRPATH . '/admin/partials/templates/wp-cbb-incomplete-setup.php';
			}
		}
	}


	/**
	 * Connect page with bot using page id.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_connect_fb_page
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_connect_fb_page() {
		if ( isset( $_REQUEST['cb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cb_nonce'] ) ), 'wp-cbb-verify-nonce' ) ) {
			$response_data      = array();
			$page_id_to_connect = isset( $_REQUEST['page_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page_id'] ) ) : '';
			$response_data      = $this->api->wp_cbb_connect_page( $page_id_to_connect );
			echo json_encode( $response_data );
			wp_die();
		}
	}

	/**
	 * Save enable plugin setting data.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_enable_plugin_save
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_enable_plugin_save() {
		if ( isset( $_REQUEST['cb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cb_nonce'] ) ), 'wp-cbb-verify-nonce' ) ) {
			$check_enable_box = isset( $_REQUEST['check_enable_box'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['check_enable_box'] ) ) : 'false';
			if ( 'true' === $check_enable_box ) {
				$response_data = update_option( 'wp_cbb_enable_plugin', 'on' );
			} else {
				$response_data = update_option( 'wp_cbb_enable_plugin', 'off' );
			}
			echo json_encode( $response_data );
			wp_die();
		}
	}

	/**
	 * Save welcome message setting data.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_welcome_message_save
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_welcome_message_save() {
		if ( isset( $_REQUEST['cb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cb_nonce'] ) ), 'wp-cbb-verify-nonce' ) ) {
			$welcome_message   = isset( $_REQUEST['welcome_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['welcome_message'] ) ) : '';
			$welcom_message_id = isset( $_REQUEST['welcom_message_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['welcom_message_id'] ) ) : '';
			$response_data     = $this->api->wp_cbb_update_welcome_message( $welcome_message, $welcom_message_id );
			echo json_encode( $response_data );
			wp_die();
		}
	}

	/**
	 * Save default message setting data.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_default_message_save
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_default_message_save() {
		if ( isset( $_REQUEST['cb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cb_nonce'] ) ), 'wp-cbb-verify-nonce' ) ) {
			$default_message = isset( $_REQUEST['default_message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['default_message'] ) ) : '';
			$default_msg_id  = isset( $_REQUEST['default_msg_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['default_msg_id'] ) ) : '';
			$response_data   = $this->api->wp_cbb_update_default_message( $default_message, $default_msg_id );
			echo json_encode( $response_data );
			wp_die();
		}
	}

	/**
	 * Save question and answer setting data set.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_qna_set_save
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_qna_set_save() {
		if ( isset( $_REQUEST['cb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cb_nonce'] ) ), 'wp-cbb-verify-nonce' ) ) {
			$knowledge_id  = isset( $_REQUEST['knowledge_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['knowledge_id'] ) ) : '';
			$question_set  = ! empty( $_REQUEST['question_set'] ) ? map_deep( wp_unslash( $_REQUEST['question_set'] ), 'sanitize_text_field' ) : array();
			$answer_set    = ! empty( $_REQUEST['answer_set'] ) ? map_deep( wp_unslash( $_REQUEST['answer_set'] ), 'sanitize_text_field' ) : array();
			$response_data = $this->api->wp_cbb_update_question_answer_set( $knowledge_id, $question_set, $answer_set );
			echo json_encode( $response_data );
			wp_die();
		}
	}

	/**
	 * Remove question and answer setting data set.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_remove_qna_set
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_remove_qna_set() {
		if ( isset( $_REQUEST['cb_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['cb_nonce'] ) ), 'wp-cbb-verify-nonce' ) ) {
			$knowledge_id  = isset( $_REQUEST['knowledge_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['knowledge_id'] ) ) : '';
			$response_data = $this->api->wp_cbb_remove_question_answer_set( $knowledge_id );
			echo json_encode( $response_data );
			wp_die();
		}
	}

	/**
	 * Return footer html on admin pages.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_get_footer
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_get_footer() {
		/* translators: %s: BotMyWork Link */
		$link = sprintf(
			'<a href="%s" class="%s" target="_blank">%s</a>',
			esc_url( 'https://botmywork.com/?utm_source=wp_cbb&utm_medium=plugin' ),
			esc_attr( 'wp_cbb_footer_section_bmw_link' ),
			esc_html( __( 'BotMyWork', 'wp-chatbot-builder' ) )
		);?>
		<div class="wp_cbb_footer_section">
			<!-- translators: %s: BotMyWork Link -->
			<span class="wp_cbb_footer_section_text"><?php echo wp_kses_post( sprintf( 'WP-Chatbot Builder is Powered by %s', $link ), 'wp-chatbot-builder' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Add plugin meta links.
	 *
	 * @since 1.0.2
	 * @name wp_cbb_plugin_meta_links
	 * @author support@botmywork.com
	 * @param mixed $links Contains links.
	 * @param mixed $file Contains main file.
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_plugin_meta_links( $links, $file ) {
		if ( strpos( $file, 'wp-chatbot-builder/wp-chatbot-builder.php' ) !== false ) {
			$new_links = array(
				'knowledge_base' => '<a href="https://botmywork.com/knowledge-base/botmywork-chatbot-builder/wp-chatbot-builder-for-messenger/?utm_source=wp-chatbot-builder&utm_medium=org-plugin&utm_campaign=BMW" target="_blank"><i class="fa fa-book" style="margin-right:3px;"></i>Knowledge Base</a>',
				'video'          => '<a href="https://youtu.be/koK0_DnWMbQ" target="_blank"><i class="fa fa-video-camera" style="margin-right:3px;"></i>Video</a>',
				'support'        => '<a href="https://botmywork.com/contact-us/" target="_blank"><i class="fa fa-phone" style="margin-right:3px;"></i>Contact Us</a>',
			);

			$links = array_merge( $links, $new_links );
		}
		return $links;
	}

	/**
	 * This function is for export lead report
	 *
	 * @since 1.0.2
	 * @name wp_cbb_export_lead_report
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function wp_cbb_export_lead_report() {
		$wp_cbb_server_request = isset( $_GET['wp_cbb_csv_export'] ) ? sanitize_text_field( wp_unslash( $_GET['wp_cbb_csv_export'] ) ) : '';
		if ( 'wp_cbb_csv_leads_report' === $wp_cbb_server_request ) {
			$content        = array();
			$api            = new BotMyWorkApi();
			$get_leads_data = $api->wp_cbb_get_leads_generated();
			foreach ( $get_leads_data as $lead_value ) {
				$content[] = array(
					$lead_value['name'],
					$lead_value['gender'],
					$lead_value['locale'],
					$lead_value['timezone'],
					$lead_value['date'],
				);
			}
			$title            = array(
				__( 'Name', 'wp-chatbot-builder' ),
				__( 'Gender', 'wp-chatbot-builder' ),
				__( 'Locale', 'wp-chatbot-builder' ),
				__( 'TimeZone', 'wp-chatbot-builder' ),
				__( 'Created', 'wp-chatbot-builder' ),
			);
			$filename         = 'wp_cbb_leads_report.csv';
			$upload_dir_path  = wp_upload_dir()['basedir'] . '/';
			$error_log_folder = 'wp_cbb_import_error/';

			$import_error_dir = $upload_dir_path . $error_log_folder;
			if ( ! is_dir( $import_error_dir ) ) {
				mkdir( $import_error_dir, $permissions = 0777 );
			}

			$output = fopen( $import_error_dir . $filename, 'w' );
			fputcsv( $output, $title );
			foreach ( $content as $con ) {
				fputcsv( $output, $con );
			}
			$file_name                = sanitize_text_field( $filename );
			$upload_dir_path          = wp_upload_dir()['basedir'] . '/';
			$error_log_folder         = 'wp_cbb_import_error/';
			$path_of_file_to_download = $upload_dir_path . $error_log_folder . $file_name;

			if ( file_exists( $path_of_file_to_download ) ) {
				header( 'Content-Description: File Transfer' );
				header( 'Content-Type: application/csv' );
				header( 'Content-Disposition: attachment; filename="' . basename( $path_of_file_to_download ) . '"' );
				header( 'Expires: 0' );
				header( 'Cache-Control: must-revalidate' );
				header( 'Pragma: public' );
				header( 'Content-Length: ' . filesize( $path_of_file_to_download ) );
				readfile( $path_of_file_to_download );
				exit;
			}
		}
	}
}
