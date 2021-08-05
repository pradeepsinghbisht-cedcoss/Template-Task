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
 * This is used to define api functions
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.2
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/includes
 * @author     support@botmywork.com
 */
class BotMyWorkApi {
	/**
	 * Prefix for botmywork setting option.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	private $option_prefix = 'botmywork_';

	/**
	 * API Domain for botmywork.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	private $api_domain = 'https://apps.botmywork.com/tqybsaedvv/';

	/**
	 * Src for botmywork.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	private $src = 'https://webhook.botmywork.com/wgeorqhxwq';

	/**
	 * Stores all the active facebook pages.
	 *
	 * @since 1.0.2
	 * @var array
	 */
	private $pages = array();

	/**
	 * Stores active page value.
	 *
	 * @since 1.0.2
	 * @var boolean
	 */
	private $active_page = false;

	/**
	 * Stores plugin name.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	private $plugin_name = 'wp-chatbot-builder';

	/**
	 * Stores Facebook App Id.
	 *
	 * @since 1.0.2
	 * @var string
	 */
	public $wp_cbb_fb_app_id = '282654415633800';

	/**
	 * Return src.
	 *
	 * @since  1.0.2
	 * @return string
	 */
	private function wp_cbb_get_src() {
		return $this->src;
	}

	/**
	 * Return API Domain.
	 *
	 * @since  1.0.2
	 * @return string
	 */
	private function wp_cbb_get_api_domain() {
		return $this->api_domain;
	}

	/**
	 * Set Token after authentication with facebbok.
	 *
	 * @since  1.0.2
	 * @return boolean
	 */
	private function wp_cbb_set_token() {
		$token = filter_input( INPUT_GET, 'api_key', FILTER_SANITIZE_STRING );
		if ( $token ) {
			update_option( $this->option_prefix . 'token', $token );
			return true;
		}
		return false;
	}

	/**
	 * Return Facebook Authentication Token.
	 *
	 * @since  1.0.2
	 * @return string token
	 */
	private function wp_cbb_get_token() {
		return get_option( $this->option_prefix . 'token' );
	}

	/**
	 * Return page Id connected on admin panel.
	 *
	 * @since  1.0.2
	 * @return string page_id
	 */
	public function wp_cbb_get_active_page_id() {
		$data = get_option( $this->option_prefix . 'active_page_info' );
		return $data['page_id'];
	}

	/**
	 * Return page name connected on admin panel.
	 *
	 * @since  1.0.2
	 * @return string page_name
	 */
	public function wp_cbb_get_active_page_name() {
		$data = get_option( $this->option_prefix . 'active_page_info' );
		return $data['page_name'];
	}

	/**
	 * Return disconnect path link for facebook.
	 *
	 * @since  1.0.2
	 * @return string disconnect_link
	 */
	public function wp_cbb_get_disconnect_fb_page_path() {
		return add_query_arg(
			array(
				'page' => 'wp-chatbot-builder',
				'disconnect' => $this->wp_cbb_get_active_page_id(),
			),
			admin_url( 'admin.php' )
		);
	}


	/**
	 * Return all the pages of logged in user.
	 *
	 * @since  1.0.2
	 * @return array pages
	 */
	public function wp_cbb_get_pages() {
		$args = array(
			'timeout' => 10,
			'headers' => array(
				'BMW-API-KEY' => $this->wp_cbb_get_token(),
			),
		);
		$pages_obj = null;
		$pages = array();
		$response = wp_remote_get( $this->wp_cbb_get_src() . '/wordpress/fetchfbpages', $args );
		$pg_content = wp_remote_retrieve_body( $response );
		if ( ! empty( $pg_content ) ) {
			$pages_obj = json_decode( $pg_content, true );
			if ( isset( $pages_obj['data']['pages'] ) && is_array( $pages_obj['data']['pages'] ) && ! empty( $pages_obj['data']['pages'] ) ) {
				$fb_page_arr = $pages_obj['data']['pages'];
				foreach ( $fb_page_arr as $page ) {
					$each_page = array(
						'name' => $page['name'],
						'id' => $page['id'],
						'isSubscribed' => $page['isSubscribed'],
					);
					$pages[] = $each_page;
				}
			}
		}

		$this->pages = $pages;
		return $pages;
	}


	/**
	 * Return active page id.
	 *
	 * @since  1.0.2
	 * @param  bool $reset Should the date be deleted permanently.
	 * @return array active_page
	 */
	public function wp_cbb_get_active_page( $reset = false ) {
		if ( ! $reset && ! empty( $this->active_page ) ) {
			return $this->active_page;
		}
		$actpage = get_option( 'botmywork_active_page_info' );
		if ( $actpage ) {
			$this->active_page = $actpage;
			return $actpage;
		} else {
			return false;
		}
	}

	/**
	 * Reload the window.
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function wp_cbb_refresh_settings_page() {
		echo "<script type='text/javascript'>
				var path = location.protocol + '//' + location.host + location.pathname + '?page=" . esc_attr( $this->plugin_name ) . "';
		        window.location = path;</script>";
	}

	/**
	 * Return connect link with facebook.
	 *
	 * @since  1.0.2
	 * @return string link
	 */
	public function wp_cbb_connect_link() {
		$current_user = wp_get_current_user();
		if ( ! empty( $current_user->user_email ) ) {
			$user_email = $current_user->user_email;
		} else {
			$user_email = get_option( 'admin_email', '' );
		}
		return $this->wp_cbb_get_api_domain() . 'wordpress?callback=' . admin_url( 'admin.php' ) . '&page=' . $this->plugin_name . '&email=' . $user_email . '&v=' . WP_CBB_VERSION;
	}

	/**
	 * Connect bot with facebook page.
	 *
	 * @since  1.0.2
	 * @return string token_id
	 */
	public function wp_cbb_connect_bot_my_work() {
		if ( $this->wp_cbb_set_token() ) {
			$this->wp_cbb_refresh_settings_page();
		}
		return $this->wp_cbb_get_token();
	}

	/**
	 * Logout BMW.
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function wp_cbb_logout_bot_my_work() {
		$logout = filter_input( INPUT_GET, 'logout', FILTER_SANITIZE_STRING );
		if ( $logout ) {
			$this->wp_cbb_disconnect_page( get_option( $this->active_page['page_id'] ) );
			delete_option( $this->option_prefix . 'token' );
			$this->wp_cbb_refresh_settings_page();
		}
	}

	/**
	 * Connect facebook page with chatbot.
	 *
	 * @since  1.0.2
	 * @param  string $page_id PageId to connect.
	 * @return array $response_data.
	 */
	public function wp_cbb_connect_page( $page_id ) {
		$response_data['message'] = '';
		$response_data['status'] = false;
		if ( $page_id ) {
			set_transient( 'wp_cbb_previously_connected_page', true );
			$args = array(
				'timeout' => 10,
				'body' => json_encode(
					array(
						'pageId' => $page_id,
					)
				),
				'headers' => array(
					'BMW-API-KEY' => $this->wp_cbb_get_token(),
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'method' => 'POST',
			);
			$response = wp_remote_post( $this->wp_cbb_get_src() . '/wordpress/connectpage', $args );
			$connect_response = json_decode( wp_remote_retrieve_body( $response ) );
			if ( $connect_response->status ) {
				if ( ! empty( $connect_response->data ) ) {
					$connect_page['page_name'] = $connect_response->data->page->name;
					$connect_page['page_id'] = $connect_response->data->page->id;
					update_option( $this->option_prefix . 'active_page_info', $connect_page );
					$response_data['message'] = 'Hey, Page connected with bot successfully';
					$response_data['status'] = true;
				}
			} else {
				$error = ( null !== $connect_response->message ) ? $connect_response->message : 'API communication error. Unable to connect facebook page.';
				$response_data['message'] = $error;
			}
		}
		return $response_data;
	}

	/**
	 * Delete saved active page info
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function wp_cbb_local_disconnect_page() {
		$this->active_page = false;
		delete_option( $this->option_prefix . 'active_page_info' );
		$this->wp_cbb_refresh_settings_page();
	}

	/**
	 * Disconnect facebook page with chatbot.
	 *
	 * @since  1.0.2
	 * @param  string $page_id PageId to disconnect.
	 * @return boolean
	 */
	public function wp_cbb_disconnect_page( $page_id = '' ) {
		if ( ! $page_id ) {
			$page_id = filter_input( INPUT_GET, 'disconnect', FILTER_SANITIZE_STRING );
		}
		if ( $page_id ) {
			$previously_connected = get_transient( 'wp_cbb_previously_connected_page' );
			if ( $previously_connected ) {
				$this->wp_cbb_local_disconnect_page();
				return true;
			}
			return false;
		}
	}

	/**
	 * Update Welcome Message Setting.
	 *
	 * @since  1.0.2
	 * @param  string $new_welcome_message welcome_message.
	 * @param  string $welcom_message_id welcome_message_id.
	 * @return string status
	 */
	public function wp_cbb_update_welcome_message( $new_welcome_message, $welcom_message_id ) {
		$args = array(
			'timeout' => 10,
			'body' => json_encode(
				array(
					'pageId' => $this->wp_cbb_get_active_page_id(),
					'block' => array( 'text' => $new_welcome_message ),
					'block_id' => $welcom_message_id,
				)
			),
			'headers' => array(
				'BMW-API-KEY' => $this->wp_cbb_get_token(),
				'Content-Type' => 'application/json; charset=utf-8',
			),
			'method' => 'POST',
		);
		$response = wp_remote_post( $this->wp_cbb_get_src() . '/wordpress/block', $args );
		$response = json_decode( wp_remote_retrieve_body( $response ) );
		return $response->status;
	}

	/**
	 * Update Default Message Setting.
	 *
	 * @since  1.0.2
	 * @param  string $new_default_message default_message.
	 * @param  string $default_message_id default_message_id.
	 * @return string status
	 */
	public function wp_cbb_update_default_message( $new_default_message, $default_message_id ) {
		$args = array(
			'timeout' => 10,
			'body' => json_encode(
				array(
					'pageId' => $this->wp_cbb_get_active_page_id(),
					'block' => array( 'text' => $new_default_message ),
					'block_id' => $default_message_id,
				)
			),
			'headers' => array(
				'BMW-API-KEY' => $this->wp_cbb_get_token(),
				'Content-Type' => 'application/json; charset=utf-8',
			),
			'method' => 'POST',
		);
		$response = wp_remote_post( $this->wp_cbb_get_src() . '/wordpress/block', $args );
		$response = json_decode( wp_remote_retrieve_body( $response ) );
		return $response->status;
	}

	/**
	 * Get setup setting data.
	 *
	 * @since  1.0.2
	 * @return array $value_new
	 */
	public function wp_cbb_get_set_up_settings() {
		$value_new = array();
		$url = $this->wp_cbb_get_src() . '/wordpress/ai';
		$attr = array(
			'pageId' => $this->wp_cbb_get_active_page_id(),
		);
		$args = array(
			'timeout' => 10,
			'headers' => array(
				'BMW-API-KEY' => $this->wp_cbb_get_token(),
				'Content-Type' => 'application/json; charset=utf-8',
			),
		);
		$query = esc_url_raw( add_query_arg( $attr, $url ) );
		$response = wp_remote_get( $query, $args );

		if ( is_wp_error( $response ) ) {
			$value_new['error_message'] = $response->get_error_message();
		} else {
			$response = json_decode( wp_remote_retrieve_body( $response ) );
			if ( ! empty( $response->data->blocks ) ) {
				foreach ( $response->data->blocks as $response_val ) {
					if ( 'Welcome' === $response_val->title ) {
						if ( 'buttons' === $response_val->template_type ) {
							$value_new['cb_welcome_message'] = array(
								'message' => $response_val->text,
								'id' => $response_val->id,
								'template_type' => $response_val->template_type,
							);
						} else {
							$value_new['cb_welcome_message'] = array(
								'id' => $response_val->id,
								'template_type' => $response_val->template_type,
							);
						}
					} elseif ( 'Default' === $response_val->title ) {
						if ( 'buttons' === $response_val->template_type ) {
							$value_new['cb_default_message'] = array(
								'message' => $response_val->text,
								'id' => $response_val->id,
								'template_type' => $response_val->template_type,
							);
						} else {
							$value_new['cb_default_message'] = array(
								'id' => $response_val->id,
								'template_type' => $response_val->template_type,
							);
						}
					}
				}
			}
			if ( ! empty( $response->data->fbActions ) ) {
				$value_new['cb_qna_set'] = $response->data->fbActions;
			}
		}
		return $value_new;
	}


	/**
	 * Update Question and Answer dataset Setting.
	 *
	 * @since  1.0.2
	 * @param  string $knowledge_id knowledge_id.
	 * @param  string $question_set question_set.
	 * @param  string $answer_set answer_set.
	 * @return string status
	 */
	public function wp_cbb_update_question_answer_set( $knowledge_id, $question_set, $answer_set ) {
		$args = array(
			'timeout' => 10,
			'body' => json_encode(
				array(
					'pageId' => $this->wp_cbb_get_active_page_id(),
					'knowledgebase' => array(
						'answers' => $answer_set,
						'questions' => $question_set,
					),
					'KBID' => $knowledge_id,
				)
			),
			'headers' => array(
				'BMW-API-KEY' => $this->wp_cbb_get_token(),
				'Content-Type' => 'application/json; charset=utf-8',
			),
			'method' => 'POST',
		);
		$response = wp_remote_post( $this->wp_cbb_get_src() . '/wordpress/ai', $args );
		$response = json_decode( wp_remote_retrieve_body( $response ) );
		return $response->status;
	}


	/**
	 * Remove Question and Answer dataset Setting.
	 *
	 * @since  1.0.2
	 * @param  string $knowledge_id knowledge_id.
	 * @return string status
	 */
	public function wp_cbb_remove_question_answer_set( $knowledge_id ) {
		$url = $this->wp_cbb_get_src() . '/wordpress/ai';
		$attr = array(
			'pageId' => $this->wp_cbb_get_active_page_id(),
			'KBID' => $knowledge_id,
		);
		$args = array(
			'timeout' => 10,
			'headers' => array(
				'BMW-API-KEY' => $this->wp_cbb_get_token(),
				'Content-Type' => 'application/json; charset=utf-8',
			),
			'method' => 'DELETE',
		);
		$query = esc_url_raw( add_query_arg( $attr, $url ) );
		$response = wp_remote_request( $query, $args );
		$response = json_decode( wp_remote_retrieve_body( $response ) );
		return $response->status;
	}

	/**
	 * Returns leads generated.
	 *
	 * @since  1.0.2
	 * @return array $leads_data
	 */
	public function wp_cbb_get_leads_generated() {
		$leads_data = array();
		$url = $this->wp_cbb_get_src() . '/wordpress/leads';
		$attr = array(
			'pageId' => $this->wp_cbb_get_active_page_id(),
		);
		$args = array(
			'timeout' => 10,
			'headers' => array(
				'BMW-API-KEY' => $this->wp_cbb_get_token(),
				'Content-Type' => 'application/json; charset=utf-8',
			),
		);
		$query = esc_url_raw( add_query_arg( $attr, $url ) );
		$response = wp_remote_get( $query, $args );
		$response = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( ! empty( $response['data'] ) ) {
			if ( ! empty( $response['data']['items'] ) ) {
				foreach ( $response['data']['items'] as $data_val ) {
					$leads_data[]  = array(
						'name'     => ( array_key_exists( 'name', $data_val['details'] ) && isset( $data_val['details']['name'] ) ) ? $data_val['details']['name'] : '-',
						'gender'   => ( array_key_exists( 'gender', $data_val['details'] ) && isset( $data_val['details']['gender'] ) ) ? $data_val['details']['gender'] : '-',
						'locale'   => ( array_key_exists( 'locale', $data_val['details'] ) && isset( $data_val['details']['locale'] ) ) ? $data_val['details']['locale'] : '-',
						'timezone' => ( array_key_exists( 'timezone', $data_val['details'] ) && isset( $data_val['details']['timezone'] ) ) ? $data_val['details']['timezone'] : '-',
						'date'     => ( array_key_exists( 'date', $data_val ) && isset( $data_val['date'] ) ) ? $data_val['date'] : '-',
					);
				}
			}
		}
		return $leads_data;
	}
}
