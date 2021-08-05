<?php
/**
 * The admin-specific on-boarding functionality of the plugin.
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package     WP_Chatbot_Builder
 * @subpackage  WP_Chatbot_Builder/includes
 */

/**
 * The Onboarding-specific functionality of the plugin admin side.
 *
 * @package     WP_Chatbot_Builder
 * @subpackage  WP_Chatbot_Builder/includes
 * @author      support@botmywork.com
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( class_exists( 'WP_Chatbot_Builder_Onboarding_Steps' ) ) {
	return;
}
/**
 * Define class and module for onboarding steps.
 */
class WP_Chatbot_Builder_Onboarding_Steps {

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.2
	 * @var $_instance object of onboarding.
	 */
	protected static $_instance = null;

	/**
	 * Base url of hubspot api for wp-chatbot-builder.
	 *
	 * @since 1.0.2
	 * @var string base url of API.
	 */
	private $bmw_wcb_base_url = 'https://api.hsforms.com/';

	/**
	 * Portal id of hubspot api for wp-chatbot-builder.
	 *
	 * @since 1.0.2
	 * @var string Portal id.
	 */
	private static $bmw_wcb_portal_id = '6969293';

	/**
	 * Form id of hubspot api for wp-chatbot-builder.
	 *
	 * @since 1.0.2
	 * @var string Form id.
	 */
	private static $bmw_wcb_onboarding_form_id = 'cfdf6cf0-dc7b-4fe0-8149-476ecb023dd3';

	/**
	 * Form id of hubspot api for wp-chatbot-builder.
	 *
	 * @since 1.0.2
	 * @var string Form id.
	 */
	private static $bmw_wcb_deactivation_form_id = 'b1a533e8-bb21-4fb4-8bbc-c344f16aa508';

	/**
	 * Define some variables for wp-chatbot-builder.
	 *
	 * @since 1.0.2
	 * @var string $bmw_wcb_plugin_name plugin name.
	 */
	private static $bmw_wcb_plugin_name;

	/**
	 * Define some variables for wp-chatbot-builder.
	 *
	 * @since 1.0.2
	 * @var string $bmw_wcb_plugin_name_label plugin name text.
	 */
	private static $bmw_wcb_plugin_name_label;

	/**
	 * Define some variables for wp-chatbot-builder.
	 *
	 * @var string $bmw_wcb_store_name store name.
	 * @since 1.0.2
	 */
	private static $bmw_wcb_store_name;

	/**
	 * Define some variables for wp-chatbot-builder.
	 *
	 * @since 1.0.2
	 * @var string $bmw_wcb_store_url store url.
	 */
	private static $bmw_wcb_store_url;

	/**
	 * Define the onboarding functionality of the plugin.
	 *
	 * Set the plugin name and the store name and store url that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area.
	 *
	 * @since    1.0.2
	 */
	public function __construct() {
		self::$bmw_wcb_store_name = get_bloginfo( 'name' );
		self::$bmw_wcb_store_url = home_url();
		self::$bmw_wcb_plugin_name = 'WP-Chatbot Builder';
		self::$bmw_wcb_plugin_name_label = 'WP-Chatbot Builder';

		add_action( 'admin_enqueue_scripts', array( $this, 'bmw_wcb_onboarding_enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'bmw_wcb_onboarding_enqueue_scripts' ) );
		add_action( 'admin_footer', array( $this, 'bmw_wcb_add_onboarding_popup_screen' ) );
		add_action( 'admin_footer', array( $this, 'bmw_wcb_add_deactivation_popup_screen' ) );

		add_filter( 'bmw_wcb_on_boarding_form_fields', array( $this, 'bmw_wcb_add_on_boarding_form_fields' ) );
		add_filter( 'bmw_wcb_deactivation_form_fields', array( $this, 'bmw_wcb_add_deactivation_form_fields' ) );

		// Ajax to send data.
		add_action( 'wp_ajax_bmw_wcb_send_onboarding_data', array( $this, 'bmw_wcb_send_onboarding_data' ) );
		add_action( 'wp_ajax_nopriv_bmw_wcb_send_onboarding_data', array( $this, 'bmw_wcb_send_onboarding_data' ) );

		// Ajax to Skip popup.
		add_action( 'wp_ajax_wcb_skip_onboarding_popup', array( $this, 'bmw_wcb_skip_onboarding_popup' ) );
		add_action( 'wp_ajax_nopriv_wcb_skip_onboarding_popup', array( $this, 'bmw_wcb_skip_onboarding_popup' ) );

	}

	/**
	 * Main Onboarding steps Instance.
	 *
	 * Ensures only one instance of Onboarding functionality is loaded or can be loaded.
	 *
	 * @since 1.0.2
	 * @static
	 * @return Onboarding Steps - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Botmywork_Onboarding_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Botmywork_Onboarding_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	public function bmw_wcb_onboarding_enqueue_styles() {
		global $pagenow;
		$is_valid = false;
		if ( ! $is_valid && 'plugins.php' == $pagenow ) {
			$is_valid = true;
		}
		if ( $this->bmw_wcb_valid_page_screen_check() || $is_valid ) {
			// comment the line of code Only when your plugin doesn't uses the Select2.
			wp_enqueue_style( 'bmw-wcb-onboarding-select2-style', WP_CBB_URL . 'package/lib/select-2/wp-chatbot-builder-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'bmw-wcb-meterial-css', WP_CBB_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'bmw-wcb-meterial-css2', WP_CBB_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'bmw-wcb-meterial-lite', WP_CBB_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'bmw-wcb-meterial-icons-css', WP_CBB_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( 'bmw-wcb-onboarding-style', WP_CBB_URL . 'onboarding/css/wp-chatbot-builder-onboarding.css', array(), time(), 'all' );

		}
	}

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Botmywork_Onboarding_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Botmywork_Onboarding_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	public function bmw_wcb_onboarding_enqueue_scripts() {
		global $pagenow;
		$is_valid = false;
		if ( ! $is_valid && 'plugins.php' == $pagenow ) {
			$is_valid = true;
		}
		if ( $this->bmw_wcb_valid_page_screen_check() || $is_valid ) {

			wp_enqueue_script( 'bmw-wcb-onboarding-select2-js', WP_CBB_URL . 'package/lib/select-2/wp-chatbot-builder-select2.js', array( 'jquery' ), '1.0.2', false );

			wp_enqueue_script( 'bmw-wcb-metarial-js', WP_CBB_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'bmw-wcb-metarial-js2', WP_CBB_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'bmw-wcb-metarial-lite', WP_CBB_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_enqueue_script( 'bmw-wcb-onboarding-scripts', WP_CBB_URL . 'onboarding/js/wp-chatbot-builder-onboarding.js', array( 'jquery', 'bmw-wcb-onboarding-select2-js', 'bmw-wcb-metarial-js', 'bmw-wcb-metarial-js2', 'bmw-wcb-metarial-lite' ), time(), false );
			$wcb_current_slug = ! empty( explode( '/', plugin_basename( __FILE__ ) ) ) ? explode( '/', plugin_basename( __FILE__ ) )[0] : '';
			wp_localize_script(
				'bmw-wcb-onboarding-scripts',
				'bmw_wcb_onboarding',
				array(
					'ajaxurl'                    => admin_url( 'admin-ajax.php' ),
					'wcb_auth_nonce'             => wp_create_nonce( 'bmw_wcb_onboarding_nonce' ),
					'wcb_current_screen'         => $pagenow,
					'wcb_current_supported_slug' => apply_filters( 'bmw_wcb_deactivation_supported_slug', array( $wcb_current_slug ) ),
				)
			);
		}
	}

	/**
	 * Get all valid screens to add scripts and templates for wp-chatbot-builder.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_add_onboarding_popup_screen() {
		if ( $this->bmw_wcb_valid_page_screen_check() && $this->bmw_wcb_show_onboarding_popup_check() ) {
			require_once WP_CBB_DIRPATH . 'onboarding/templates/wp-chatbot-builder-onboarding-template.php';
		}
	}

	/**
	 * Get all valid screens to add scripts and templates for wp-chatbot-builder.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_add_deactivation_popup_screen() {
		global $pagenow;
		if ( ! empty( $pagenow ) && 'plugins.php' == $pagenow ) {
			require_once WP_CBB_DIRPATH . 'onboarding/templates/wp-chatbot-builder-deactivation-template.php';
		}
	}

	/**
	 * Skip the popup for some days of wp-chatbot-builder.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_skip_onboarding_popup() {

		$get_skipped_timstamp = update_option( 'bmw_wcb_onboarding_data_skipped', time() );
		echo json_encode( 'true' );
		wp_die();
	}


	/**
	 * Add your wp-chatbot-builder onboarding form fields.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_add_on_boarding_form_fields() {
		$current_user = wp_get_current_user();
		if ( ! empty( $current_user ) ) {
			$current_user_email = $current_user->user_email ? $current_user->user_email : '';
		}

		if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
			$currency_symbol = get_woocommerce_currency_symbol();
		} else {
			$currency_symbol = '$';
		}

		/**
		 * Do not repeat id index.
		 */

		$fields = array(

			/**
			 * Input field with label.
			 * Radio field with label ( select only one ).
			 * Radio field with label ( select multiple one ).
			 * Checkbox radio with label ( select only one ).
			 * Checkbox field with label ( select multiple one ).
			 * Only Label ( select multiple one ).
			 * Select field with label ( select only one ).
			 * Select2 field with label ( select multiple one ).
			 * Email field with label. ( auto filled with admin email )
			 */

			rand() => array(
				'id'          => 'bmw-wcb-monthly-revenue',
				'title'       => '',
				'type'        => 'hidden',
				'description' => '',
				'name'        => 'what_is_your_monthly_revenue_',
				'value'       => '0-500',
				'placeholder' => '',
				'required'    => '',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'bmw_wcb_industry_type',
				'title'       => esc_html__( 'What industry defines your business?', 'wp-chatbot-builder' ),
				'type'        => 'select',
				'name'        => 'what_industry_defines_your_business_',
				'value'       => '',
				'description' => '',
				'multiple'    => 'yes',
				'placeholder' => esc_html__( 'Industry Type', 'wp-chatbot-builder' ),
				'required'    => 'yes',
				'class'       => '',
				'options'     => array(
					'agency'                  => 'Agency',
					'consumer-services'       => 'Consumer Services',
					'ecommerce'               => 'Ecommerce',
					'financial-services'      => 'Financial Services',
					'healthcare'              => 'Healthcare',
					'manufacturing'           => 'Manufacturing',
					'nonprofit-and-education' => 'Nonprofit and Education',
					'professional-services'   => 'Professional Services',
					'real-estate'             => 'Real Estate',
					'software'                => 'Software',
					'startups'                => 'Startups',
					'restaurant'              => 'Restaurant',
					'fitness'                 => 'Fitness',
					'jewelry'                 => 'Jewelry',
					'beauty'                  => 'Beauty',
					'celebrity'               => 'Celebrity',
					'gaming'                  => 'Gaming',
					'government'              => 'Government',
					'sports'                  => 'Sports',
					'retail-store'            => 'Retail Store',
					'travel'                  => 'Travel',
					'political-campaign'      => 'Political Campaign',
				),
			),

			rand() => array(
				'id' => 'bmw-wcb-onboard-email',
				'title' => esc_html__( 'What is the best email address to contact you?', 'wp-chatbot-builder' ),
				'type' => 'email',
				'description' => '',
				'name' => 'email',
				'placeholder' => esc_html__( 'Email', 'wp-chatbot-builder' ),
				'value' => $current_user_email,
				'required' => 'yes',
				'class' => 'wcb-text-class',
			),

			rand() => array(
				'id' => 'bmw-wcb-onboard-number',
				'title' => esc_html__( 'What is your contact number?', 'wp-chatbot-builder' ),
				'type' => 'text',
				'description' => '',
				'name' => 'phone',
				'value' => '',
				'placeholder' => esc_html__( 'Contact Number', 'wp-chatbot-builder' ),
				'required' => 'yes',
				'class' => '',
			),

			rand() => array(
				'id' => 'bmw-wcb-store-name',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'name' => 'company',
				'placeholder' => '',
				'value' => self::$bmw_wcb_store_name,
				'required' => '',
				'class' => '',
			),

			rand() => array(
				'id' => 'bmw-wcb-store-url',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'name' => 'website',
				'placeholder' => '',
				'value' => self::$bmw_wcb_store_url,
				'required' => '',
				'class' => '',
			),

			rand() => array(
				'id' => 'bmw-wcb-show-counter',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'placeholder' => '',
				'name' => 'bmw-wcb-show-counter',
				'value' => get_option( 'bmw_wcb_onboarding_data_sent', 'not-sent' ),
				'required' => '',
				'class' => '',
			),

			rand() => array(
				'id' => 'bmw-wcb-plugin-name',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'placeholder' => '',
				'name' => 'org_plugin_name',
				'value' => self::$bmw_wcb_plugin_name,
				'required' => '',
				'class' => '',
			),
		);
		return $fields;
	}


	/**
	 * Add your wp-chatbot-builder deactivation form fields.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_add_deactivation_form_fields() {

		$current_user = wp_get_current_user();
		if ( ! empty( $current_user ) ) {
			$current_user_email = $current_user->user_email ? $current_user->user_email : '';
		}

		/**
		 * Do not repeat id index.
		 */

		$fields = array(

			/**
			 * Input field with label.
			 * Radio field with label ( select only one ).
			 * Radio field with label ( select multiple one ).
			 * Checkbox radio with label ( select only one ).
			 * Checkbox field with label ( select multiple one ).
			 * Only Label ( select multiple one ).
			 * Select field with label ( select only one ).
			 * Select2 field with label ( select multiple one ).
			 * Email field with label. ( auto filled with admin email )
			 */

			rand() => array(
				'id' => 'bmw-wcb-deactivation-reason',
				'title' => '',
				'description' => '',
				'type' => 'radio',
				'placeholder' => '',
				'name' => 'plugin_deactivation_reason',
				'value' => '',
				'multiple' => 'no',
				'required' => 'yes',
				'class' => 'wcb-radio-class',
				'options' => array(
					'temporary-deactivation-for-debug'      => 'It is a temporary deactivation. I am just debugging an issue.',
					'site-layout-broke'         => 'The plugin broke my layout or some functionality.',
					'complicated-configuration'         => 'The plugin is too complicated to configure.',
					'no-longer-need'        => 'I no longer need the plugin',
					'found-better-plugin'       => 'I found a better plugin',
					'other'         => 'Other',
				),
			),

			rand() => array(
				'id' => 'bmw-wcb-deactivation-reason-text',
				'title' => esc_html__( 'Let us know why you are deactivating ' . self::$bmw_wcb_plugin_name_label . ' so we can improve the plugin', 'wp-chatbot-builder' ),
				'type' => 'textarea',
				'description' => '',
				'name' => 'deactivation_reason_text',
				'placeholder' => esc_html__( 'Reason', 'wp-chatbot-builder' ),
				'value' => '',
				'required' => '',
				'class' => 'bmw-keep-hidden',
			),

			rand() => array(
				'id' => 'bmw-wcb-admin-email',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'name' => 'email',
				'placeholder' => '',
				'value' => $current_user_email,
				'required' => '',
				'class' => '',
			),

			rand() => array(
				'id' => 'bmw-wcb-store-name',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'placeholder' => '',
				'name' => 'company',
				'value' => self::$bmw_wcb_store_name,
				'required' => '',
				'class' => '',
			),

			rand() => array(
				'id' => 'bmw-wcb-store-url',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'name' => 'website',
				'placeholder' => '',
				'value' => self::$bmw_wcb_store_url,
				'required' => '',
				'class' => '',
			),

			rand() => array(
				'id' => 'bmw-wcb-plugin-name',
				'title' => '',
				'description' => '',
				'type' => 'hidden',
				'placeholder' => '',
				'name' => 'org_plugin_name',
				'value' => self::$bmw_wcb_plugin_name,
				'required' => '',
				'class' => '',
			),
		);

		return $fields;
	}


	/**
	 * Send the data to Hubspot crm.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_send_onboarding_data() {

		check_ajax_referer( 'bmw_wcb_onboarding_nonce', 'nonce' );

		$form_data = ! empty( $_POST['form_data'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['form_data'] ) ) ) : '';

		$formatted_data = array();

		if ( ! empty( $form_data ) && is_array( $form_data ) ) {

			foreach ( $form_data as $key => $input ) {

				if ( 'bmw-wcb-show-counter' == $input->name ) {
					continue;
				}

				if ( false !== strrpos( $input->name, '[]' ) ) {

					$new_key = str_replace( '[]', '', $input->name );
					$new_key = str_replace( '"', '', $new_key );

					array_push(
						$formatted_data,
						array(
							'name'  => $new_key,
							'value' => $input->value,
						)
					);

				} else {

					$input->name = str_replace( '"', '', $input->name );

					array_push(
						$formatted_data,
						array(
							'name'  => $input->name,
							'value' => $input->value,
						)
					);
				}
			}
		}

		try {

			$found = current(
				array_filter(
					$formatted_data,
					function( $item ) {
						return isset( $item['name'] ) && 'plugin_deactivation_reason' == $item['name'];
					}
				)
			);

			if ( ! empty( $found ) ) {
				$action_type = 'deactivation';
			} else {
				$action_type = 'onboarding';
			}

			if ( ! empty( $formatted_data ) && is_array( $formatted_data ) ) {

				unset( $formatted_data['bmw-wcb-show-counter'] );

				$result = $this->bmw_wcb_handle_form_submission_for_hubspot( $formatted_data, $action_type );
			}
		} catch ( Exception $e ) {

			echo json_encode( $e->getMessage() );
			wp_die();
		}

		if ( ! empty( $action_type ) && 'onboarding' == $action_type && ! empty( $formatted_data[2]['value'] ) ) {
			$get_skipped_timstamp = update_option( 'bmw_wcb_onboarding_data_sent', 'sent' );
		}

		echo json_encode( $formatted_data );
		wp_die();
	}

	/**
	 * Handle wp-chatbot-builder form submission.
	 *
	 * @param      bool   $submission       The resultant data of the form.
	 * @param      string $action_type      Type of action.
	 * @since    1.0.2
	 */
	protected function bmw_wcb_handle_form_submission_for_hubspot( $submission = false, $action_type = 'onboarding' ) {

		if ( 'onboarding' == $action_type ) {
			array_push(
				$submission,
				array(
					'name'  => 'currency',
					'value' => $this->bmw_wcb_get_currency_symbol(),
				)
			);
		}

		$result = $this->bmw_wcb_hubwoo_submit_form( $submission, $action_type );

		if ( true == $result['success'] ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 *  Define wp-chatbot-builder Onboarding Submission :: Get a form.
	 *
	 * @param      array  $form_data    form data.
	 * @param      string $action_type    type of action.
	 * @since       1.0.2
	 */
	protected function bmw_wcb_hubwoo_submit_form( $form_data = array(), $action_type = 'onboarding' ) {

		if ( 'onboarding' == $action_type ) {
			$form_id = self::$bmw_wcb_onboarding_form_id;
		} else {
			$form_id = self::$bmw_wcb_deactivation_form_id;
		}

		$url = 'submissions/v3/integration/submit/' . self::$bmw_wcb_portal_id . '/' . $form_id;

		$headers = array(
			'Content-Type' => 'application/json',
		);

		$form_data = json_encode(
			array(
				'fields' => $form_data,
				'context'  => array(
					'pageUri' => self::$bmw_wcb_store_url,
					'pageName' => self::$bmw_wcb_store_name,
					'ipAddress' => $this->bmw_wcb_get_client_ip(),
				),
			)
		);

		$response = $this->bmw_wcb_hic_post( $url, $form_data, $headers );

		if ( 200 == $response['status_code'] ) {
			$result = json_decode( $response['response'], true );
			$result['success'] = true;
		} else {
			$result = $response;
		}

		return $result;
	}

	/**
	 * Handle Hubspot POST api calls.
	 *
	 * @since    1.0.2
	 * @param   string $endpoint   Url where the form data posted.
	 * @param   array  $post_params    form data that need to be send.
	 * @param   array  $headers    data that must be included in header for request.
	 */
	private function bmw_wcb_hic_post( $endpoint, $post_params, $headers ) {

		$url      = $this->bmw_wcb_base_url . $endpoint;
		$request  = array(
			'httpversion' => '1.0',
			'sslverify'   => false,
			'method'      => 'POST',
			'timeout'     => 45,
			'headers'     => $headers,
			'body'        => $post_params,
			'cookies'     => array(),
		);

		$response = wp_remote_post( $url, $request );

		if ( is_wp_error( $response ) ) {

			$status_code = 500;
			$response    = esc_html__( 'Unexpected Error Occured', 'wp-chatbot-builder' );
			$curl_errors = $response;

		} else {

			$response    = wp_remote_retrieve_body( $response );
			$status_code = wp_remote_retrieve_response_code( $response );
			$curl_errors = $response;

		}

		return array(
			'status_code' => $status_code,
			'response'    => $response,
			'errors'      => $curl_errors,
		);
	}


	/**
	 * Function to get the client IP address.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_get_client_ip() {
		$ipaddress = '';
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} else if ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} else if ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} else if ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} else if ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} else if ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		} else {
			$ipaddress = 'UNKNOWN';
		}
		return $ipaddress;
	}

	/**
	 * Validate the popup to be shown on specific screen.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_valid_page_screen_check() {
		$bmw_wcb_screen = get_current_screen();
		$bmw_wcb_is_flag = false;
		if ( isset( $bmw_wcb_screen->id ) && 'toplevel_page_wp-chatbot-builder' == $bmw_wcb_screen->id ) {
			$bmw_wcb_is_flag = true;
		}

		return $bmw_wcb_is_flag;
	}

	/**
	 * Show the popup based on condition.
	 *
	 * @since    1.0.2
	 */
	public function bmw_wcb_show_onboarding_popup_check() {

		$bmw_wcb_is_already_sent = get_option( 'bmw_wcb_onboarding_data_sent', false );

		// Already submitted the data.
		if ( ! empty( $bmw_wcb_is_already_sent ) && 'sent' == $bmw_wcb_is_already_sent ) {
			return false;
		}

		$bmw_wcb_get_skipped_timstamp = get_option( 'bmw_wcb_onboarding_data_skipped', false );
		if ( ! empty( $bmw_wcb_get_skipped_timstamp ) ) {

			$bmw_wcb_next_show = strtotime( '+2 days', $bmw_wcb_get_skipped_timstamp );

			$bmw_wcb_current_time = time();

			$bmw_wcb_time_diff = $bmw_wcb_next_show - $bmw_wcb_current_time;

			if ( 0 < $bmw_wcb_time_diff ) {
				return false;
			}
		}

		// By default Show.
		return true;
	}

	/**
	 * Return Currency Symbol.
	 *
	 * @since    1.0.2
	 * @return string $currency_symbol currency_symbol.
	 */
	public function bmw_wcb_get_currency_symbol() {

		if ( function_exists( 'get_woocommerce_currency' ) ) {
			$currency_symbol = get_woocommerce_currency();
		} else {
			$currency_symbol = '$';
		}
		return $currency_symbol;

	}
}
