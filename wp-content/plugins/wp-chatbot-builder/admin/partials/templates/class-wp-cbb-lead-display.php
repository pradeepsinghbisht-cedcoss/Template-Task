<?php
/**
 * Exit if accessed directly
 *
 * @since      1.0.2
 * @package    WP_Chatbot_Builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * This is construct of class where all users point listed.
 *
 * @name WP_CBB_Lead_Display
 * @since      1.0.2
 * @package    WP_Chatbot_Builder
 * @author support@botmywork.com
 * @link https://botmywork.com/
 */
class WP_CBB_Lead_Display extends WP_List_Table {
	/**
	 * This is variable which is used for the store all the data.
	 *
	 * @var array $example_data variable for store data.
	 */
	public $example_data;

	/**
	 * This construct colomns in lead table.
	 *
	 * @name get_columns.
	 * @since      1.0.2
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function get_columns() {

		$columns = array(
			'user_name'     => __( 'Name', 'wp-chatbot-builder' ),
			'user_gender'   => __( 'Gender', 'wp-chatbot-builder' ),
			'user_locale'   => __( 'Locale', 'wp-chatbot-builder' ),
			'user_timezone' => __( 'TimeZone', 'wp-chatbot-builder' ),
			'user_created'  => __( 'Created', 'wp-chatbot-builder' ),
		);
		return $columns;
	}

	/**
	 * This show lead table list.
	 *
	 * @name column_default.
	 * @since      1.0.2
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 * @param array  $item  array of the items.
	 * @param string $column_name name of the colmn.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'user_name':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'user_gender':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'user_locale':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'user_timezone':
				return '<b>' . $item[ $column_name ] . '</b>';
			case 'user_created':
				return '<b>' . $item[ $column_name ] . '</b>';
			default:
				return false;
		}
	}

	/**
	 * Returns an associative array containing the bulk action for sorting.
	 *
	 * @name get_sortable_columns.
	 * @since      1.0.2
	 * @return array
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'user_name'     => array( 'user_name', false ),
			'user_gender'   => array( 'user_gender', false ),
			'user_locale'   => array( 'user_locale', false ),
			'user_timezone' => array( 'user_timezone', false ),
			'user_created'  => array( 'user_created', false ),
		);
		return $sortable_columns;
	}


	/**
	 * Prepare items for sorting.
	 *
	 * @name prepare_items.
	 * @since      1.0.2
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function prepare_items() {
		$per_page              = 10;
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->example_data    = $this->get_leads_data();
		$data                  = $this->example_data;

		$current_page = $this->get_pagenum();
		$total_items  = count( $data );
		$data         = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
		$this->items  = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);
	}

	/**
	 * This function return the leads generated.
	 *
	 * @name get_leads_data.
	 * @since      1.0.2
	 * @return array
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function get_leads_data() {
		$generated_leads = array();
		$api             = new BotMyWorkApi();
		$get_leads_data  = $api->wp_cbb_get_leads_generated();
		foreach ( $get_leads_data as $lead_value ) {
			$generated_leads[] = array(
				'user_name'     => $lead_value['name'],
				'user_gender'   => $lead_value['gender'],
				'user_locale'   => $lead_value['locale'],
				'user_timezone' => $lead_value['timezone'],
				'user_created'  => $lead_value['date'],
			);
		}
		return $generated_leads;
	}

	/**
	 * This function is to add Export Button.
	 *
	 * @param string $which place of button.
	 * @name extra_tablenav.
	 * @since      1.0.2
	 * @author support@botmywork.com
	 * @link https://botmywork.com/
	 */
	public function extra_tablenav( $which ) {
		?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=wp-chatbot-builder&wp_cbb_csv_export=wp_cbb_csv_leads_report' ) ); ?>" class="button action" target="_blank"><?php esc_html_e( 'Export CSV', 'wp-chatbot-builder' ); ?> </a>
		<?php
	}

}
?>
<div class="wp_cbb_lead_display_wrapper">
	<form method="post">
		<?php
		$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		?>
		<input type="hidden" name="page" value="<?php echo esc_attr( $current_page ); ?>">
		<?php
		wp_nonce_field( 'wp-cbb-leads', 'wp-cbb-leads' );
		$mylisttable = new WP_CBB_Lead_Display();
		$mylisttable->prepare_items();
		$mylisttable->display();
		?>
	</form>
</div>
