<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/public
 * @author     support@botmywork.com
 */
class WP_Chatbot_Builder_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.2
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api = new BotMyWorkApi();
		$this->fb_page_id = $this->api->wp_cbb_get_active_page_id();
	}

	/**
	 * Add Facebook script to display chat widget.
	 */
	public function wp_cbb_add_fb_msgr_script() {
		// Check device settings.
		$device = $this->wp_cbb_check_device();
		if ( ! $device ) {
			return;
		}
		$show = $this->wp_cbb_check_pages();
		if ( $show ) :
			wp_enqueue_script( $this->plugin_name . '-facebook-sdk', plugin_dir_url( __FILE__ ) . 'js/wp-cbb-facebook-js-sdk.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name . '-facebook-sdk',
				'wp_cbb_script_obj',
				array(
					'fb_app_id' => $this->api->wp_cbb_fb_app_id,
				)
			);
		endif;
	}

	/**
	 * Add Facebook Chat Widget Button.
	 */
	public function wp_cbb_add_fb_msgr_btn() {
		// Check device settings.
		$device = $this->wp_cbb_check_device();
		if ( ! $device ) {
			return;
		}
		$show = $this->wp_cbb_check_pages();
		if ( $show ) :
			$color                  = get_option( 'wp_cbb_plug_color_picker', '' );
			$wp_cbb_text_logged_in  = get_option( 'wp_cbb_greeting_text_logged_in', '' );
			$wp_cbb_text_logged_out = get_option( 'wp_cbb_greeting_text_logged_out', '' );
			$wp_cbb_dialog_delay    = get_option( 'wp_cbb_dialog_delay', '' );
			?>
			<div id="fb-root"></div>
			<div class="fb-customerchat"
			attribution=setup_tool
			page_id="<?php echo esc_attr( $this->fb_page_id ); ?>"
			theme_color="<?php echo esc_attr( $color ); ?>"
			logged_in_greeting="<?php echo esc_attr( $wp_cbb_text_logged_in ); ?>"
			logged_out_greeting="<?php echo esc_attr( $wp_cbb_text_logged_out ); ?>"
			greeting_dialog_delay="<?php echo esc_attr( $wp_cbb_dialog_delay ); ?>">	
			</div>    
			<?php
		endif;
	}

	/**
	 * Callable function for displaying Facebook Messenger according to device settings..
	 */
	private function wp_cbb_check_device() {
		$device_display = get_option( 'wp_cbb_plug_device_display', 'device_all' );
		if ( 'device_all' == $device_display ) {
			return true;
		} elseif ( 'device_desk' == $device_display ) {
			if ( wp_is_mobile() ) {
				return false;
			} else {
				return true;
			}
		} elseif ( 'device_mob' == $device_display ) {
			if ( wp_is_mobile() ) {
				return true;
			} else {
				return false;
			}
		}
		// fallback : if no condition matches for device display return true.
		return true;
	}

	/**
	 * Callable function for conditional displaying of Facebook Messenger.
	 */
	private function wp_cbb_check_pages() {
		$display = get_option( 'wp_cbb_plug_display', 'eve' );
		if ( 'eve' === $display ) {
			return true;
		} else if ( 'eve_except' === $display ) {
			if ( is_front_page() ) {
				$home_for_except = get_option( 'wp_cbb_home_for_except', 'yes' );
				if ( 'yes' === $home_for_except ) {
					return true;
				} else {
					return false;
				}
			}
			if ( wp_cbb_woocommerce_active() ) {
				if ( is_shop() ) {
					$except_pages = (array) get_option( 'wp_cbb_plug_display_except_pages', '' );
					if ( in_array( 'shop', $except_pages ) ) {
						return false;
					} else {
						return true;
					}
				}
			}
			global $post;
			if ( ! $post || ! $post->post_name || ! is_singular() ) {
				return true;
			}
			$post_slug = $post->post_name;
			$except_pages = (array) get_option( 'wp_cbb_plug_display_except_pages', '' );
			if ( in_array( $post_slug, $except_pages ) ) {
				return false;
			} else {
				return true;
			}
		} else if ( 'only_these' === $display ) {
			if ( is_front_page() ) {
				$home_for_except = get_option( 'wp_cbb_home_for_only', 'yes' );
				if ( 'yes' === $home_for_except ) {
					return true;
				} else {
					return false;
				}
			}
			if ( wp_cbb_woocommerce_active() ) {
				if ( is_shop() ) {
					$only_pages = (array) get_option( 'wp_cbb_plug_display_only_pages', '' );
					if ( in_array( 'shop', $only_pages ) ) {
						return true;
					} else {
						return false;
					}
				}
			}
			global $post;
			if ( ! $post || ! $post->post_name || ! is_singular() ) {
				return false;
			}
			$post_slug = $post->post_name;
			$only_pages = (array) get_option( 'wp_cbb_plug_display_only_pages', '' );
			if ( in_array( $post_slug, $only_pages ) ) {
				return true;
			} else {
				return false;
			}
		} else if ( 'only_home' === $display ) {
			if ( is_front_page() ) {
				return true;
			} else {
				return false;
			}
		}
		// fallback : if no condition matches for display then return true.
		return true;
	}
}
