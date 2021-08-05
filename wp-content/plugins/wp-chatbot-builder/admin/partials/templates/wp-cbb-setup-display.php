<?php
/**
 * Exit if accessed directly
 *
 * @package    WP_Chatbot_Builder
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://botmywork.com/
 * @since      1.0.2
 *
 * @package    WP_Chatbot_Builder
 * @subpackage WP_Chatbot_Builder/admin/partials
 */

$wp_cbb_enable   = get_option( 'wp_cbb_enable_plugin', 'off' );
$setup_data      = $this->api->wp_cbb_get_set_up_settings();
$qna_data_set    = ( ! empty( $setup_data['cb_qna_set'] ) ) ? $setup_data['cb_qna_set'] : array();
$welcome_message = ( ! empty( $setup_data['cb_welcome_message'] ) ) ? $setup_data['cb_welcome_message'] : array();
$default_message = ( ! empty( $setup_data['cb_default_message'] ) ) ? $setup_data['cb_default_message'] : array();

if ( ! empty( $setup_data['error_message'] ) ) : ?>
	<div class="wp_cbb_setup_error">
		<strong><?php echo esc_html( 'Something went wrong: ' . $setup_data['error_message'] ); ?></strong>
	</div>
<?php endif; ?>
<div class="wp_cbb_setup_wrapper"> 
	<div class="wp_cbb_setup_setting_wrap">
		<table class="form-table wp_cbb_setup_table">
			<tbody>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_enable_plugin"><?php esc_html_e( 'Enable', 'wp-chatbot-builder' ); ?></label>  
					</th>
					<td>
						<input type="checkbox" <?php echo esc_attr( 'on' == $wp_cbb_enable ) ? "checked='checked'" : ''; ?> name="wp_cbb_enable_plugin" id="wp_cbb_enable_plugin" class="input-text"> <?php esc_html_e( 'Enable WP-Chatbot Builder', 'wp-chatbot-builder' ); ?>
					</td>
					<td>
						<input type="button" name="wp_cbb_enable_plugin_save" id="wp_cbb_enable_plugin_save" class="button" value="<?php esc_attr_e( 'Save', 'wp-chatbot-builder' ); ?>">
						<div style="display: none;" class="wp-cbb-loader" id="wp_cbb_enable_plugin_save_loader">
							<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/loading.gif' ); ?>">
						</div>
					</td>
				</tr>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_welcome_message"><?php esc_html_e( 'Welcome Message', 'wp-chatbot-builder' ); ?></label>  
					</th>
					<td class="forminp forminp-text">
						<?php
						$welcome_message_text        = '';
						$welcome_block_error_message = '';
						if ( ! empty( $welcome_message ) ) :
							?>
							<input type="hidden" name="wp_cbb_wlcm_msg_id" id="wp_cbb_wlcm_msg_id" value="<?php echo esc_attr( $welcome_message['id'] ); ?>"> 
							<?php
							if ( 'buttons' === $welcome_message['template_type'] ) {
								$welcome_message_text = $welcome_message['message'];
							} else {
								$welcome_block_error_message = 'Current Welcome Message Block is not set as a string. Type here to overwrite the Current Welcome Message Block.';
							}
						endif;
						?>
						<textarea cols="47" rows="3" name="wp_cbb_welcome_message" id="wp_cbb_welcome_message"><?php echo esc_html( $welcome_message_text ); ?></textarea>
						<p class="wp_cbb_welcome_block_error_message">
							<?php echo esc_html( $welcome_block_error_message ); ?>
						</p>
					</td>
					<td>
						<input type="button" name="wp_cbb_welcome_message_save" id="wp_cbb_welcome_message_save" class="button" value="<?php esc_attr_e( 'Save', 'wp-chatbot-builder' ); ?>">
						<div style="display: none;" class="wp-cbb-loader" id="wp_cbb_welcome_message_save_loader">
							<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/loading.gif' ); ?>">
						</div>
					</td>
				</tr>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_default_message"><?php esc_html_e( 'Default Message', 'wp-chatbot-builder' ); ?></label>  
					</th>
					<td class="forminp forminp-text">    
						<?php
						$default_message_text        = '';
						$default_block_error_message = '';
						if ( ! empty( $default_message ) ) :
							?>
							<input type="hidden" name="wp_cbb_default_msg_id" id="wp_cbb_default_msg_id" value="<?php echo esc_attr( $default_message['id'] ); ?>"> 
							<?php
							if ( 'buttons' === $default_message['template_type'] ) {
								$default_message_text = $default_message['message'];
							} else {
								$default_block_error_message = 'Current Default Message Block is not set as a string. Type here to overwrite the Current Default Message Block.';
							}
						endif;
						?>
						<textarea cols="47" rows="3" name="wp_cbb_default_message" id="wp_cbb_default_message"><?php echo esc_html( $default_message_text ); ?></textarea>
						<p class="wp_cbb_default_block_error_message">
							<?php echo esc_html( $default_block_error_message ); ?>
						</p>
					</td>
					<td>
						<input type="button" name="wp_cbb_default_message_save" id="wp_cbb_default_message_save" class="button" value="<?php esc_attr_e( 'Save', 'wp-chatbot-builder' ); ?>">
						<div style="display: none;" class="wp-cbb-loader" id="wp_cbb_default_message_save_loader">
							<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/loading.gif' ); ?>">
						</div>
					</td>
				</tr>
				<tr valign="top">           
					<th scope="row" class="wp_cbb_titledesc">
						<label for="wp_cbb_qa"><?php esc_html_e( 'Q&A', 'wp-chatbot-builder' ); ?></label>  
					</th>
					<td colspan="2" class="forminp forminp-text">    
						<p id="wp_cbb_qa">
							<?php esc_html_e( 'Chatbot will answer questions on your page based on keywords detected in the user’s question.', 'wp-chatbot-builder' ); ?>
						</p>
						<div class="wp_cbb_qa_new_wrapper">
							<table class="wp_cbb_qa_table">
								<tbody class="wp_cbb_qa_tbody">  
									<tr>
										<th><?php esc_html_e( 'If user says something similar to ', 'wp-chatbot-builder' ); ?></th>
										<th><?php esc_html_e( 'Bot randomly replies with ', 'wp-chatbot-builder' ); ?></th>
										<th><?php esc_html_e( 'Action', 'wp-chatbot-builder' ); ?></th>
									</tr>
									<?php
									if ( ! empty( $qna_data_set ) ) {
										foreach ( $qna_data_set as $key => $value ) {
											?>
											<tr class="wp_cbb_qa_wrap" id="<?php echo esc_attr( $key ); ?>" data-knowledgeId="<?php echo esc_attr( $value->knowledge_id ); ?>">
												<td>
													<textarea class="wp_cbb_qa_input_questions" placeholder="e.g. 'Order', 'Refund' etc."></textarea>
													<i class="fa fa-plus wp_cbb_add_more_ques_terms" aria-hidden="true"></i>
													<div class="wp_cbb_questions_wrapper">
														<?php
														if ( ! empty( $value->questions ) ) {
															foreach ( $value->questions as $quest_key => $ques_value ) {
																?>
																<div class="wp_cbb_quest_term_wrap">
																	<span class="wp_cbb_quest_term" data-index="<?php echo esc_attr( $quest_key ); ?>"><?php echo esc_html( $ques_value ); ?></span>
																	<span class="wp_cbb_qa__bin" data-index="<?php echo esc_attr( $quest_key ); ?>">
																		<i class="fa fa-times" aria-hidden="true"></i>
																	</span>
																</div>
																<?php
															}
														}
														?>
													</div>  
													<div class="wp_cbb_questions_hidden_wrapper">
														<?php
														if ( ! empty( $value->questions ) ) {
															foreach ( $value->questions as $quest_key => $ques_value ) {
																?>
																<input type="hidden" name="wp_cbb_[<?php echo esc_attr( $key ); ?>][questions][]" value="<?php echo esc_attr( $ques_value ); ?>" id="wp_cbb_qa_<?php echo esc_attr( $key ); ?>_<?php echo esc_attr( $quest_key ); ?>">
																<?php
															}
														}
														?>
													</div>  
												</td>
												<td >
													<textarea class="wp_cbb_qa_input_answers" placeholder="Enter your answer here"></textarea>
													<i class="fa fa-plus wp_cbb_add_more_answer_terms" aria-hidden="true"></i>
													<div class="wp_cbb_response_wrapper">
														<?php
														if ( ! empty( $value->answers ) ) {
															foreach ( $value->answers as $answ_key => $answ_val ) {
																?>
															<div class="wp_cbb_qtn_answer_wrap">
																<textarea class="wp_cbb_qtn_answer" data-index="<?php echo esc_attr( $answ_key ); ?>"><?php echo esc_attr( $answ_val ); ?></textarea>
																<span class="wp_cbb_answer__bin" data-index="<?php echo esc_attr( $answ_key ); ?>">
																	<i class="fa fa-times" aria-hidden="true"></i>
																</span>
															</div>
																<?php
															}
														}
														?>
													</div>
													<div class="wp_cbb_response_hidden_wrapper">
														<?php
														if ( ! empty( $value->answers ) ) {
															foreach ( $value->answers as $answ_key => $answ_val ) {
																?>
															<input type="hidden" name="wp_cbb_[<?php echo esc_attr( $key ); ?>][answers][]" value="<?php echo esc_attr( $answ_val ); ?>" id="wp_cbb_answer_<?php echo esc_attr( $key ); ?>_<?php echo esc_attr( $answ_key ); ?>">
																<?php
															}
														}
														?>
													</div>
												</td>
												<td class="wp_cbb_remove_qa_row" style="display: table-cell;">
													<input type="button" name="wp_cbb_qna_save" class="button wp_cbb_qna_save" value="<?php esc_attr_e( 'Update', 'wp-chatbot-builder' ); ?>"> 
													<div style="display: none;" class="wp-cbb-loader" id="wp_cbb_qna_save_loader">
														<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/loading.gif' ); ?>">
													</div>
													<span class="wp_cbb_qna_remove_row">
														<i class="fa fa-trash-o" aria-hidden="true"></i>
													</span>
												</td>
											</tr>
											<?php
										}
									} else {
										$i = 0;
										?>
										<tr class="wp_cbb_qa_wrap" id="<?php echo esc_attr( $i ); ?>" data-knowledgeId="">
											<td>
												<textarea class="wp_cbb_qa_input_questions" placeholder="e.g. 'Order', 'Refund' etc."></textarea>
												<i class="fa fa-plus wp_cbb_add_more_ques_terms" aria-hidden="true"></i>
												<div class="wp_cbb_questions_wrapper"></div>  
												<div class="wp_cbb_questions_hidden_wrapper"></div>  
											</td>
											<td>
												<textarea class="wp_cbb_qa_input_answers" placeholder="Enter your answer here"></textarea>
												<i class="fa fa-plus wp_cbb_add_more_answer_terms" aria-hidden="true"></i>
												<div class="wp_cbb_response_wrapper"></div>
												<div class="wp_cbb_response_hidden_wrapper"></div>
											</td>
											<td class="wp_cbb_remove_qa_row">
												<input type="button" name="wp_cbb_qna_save" class="button wp_cbb_qna_save" value="<?php esc_attr_e( 'Update', 'wp-chatbot-builder' ); ?>">
												<div style="display: none;" class="wp-cbb-loader" id="wp_cbb_qna_save_loader">
														<img src="<?php echo esc_url( WP_CBB_URL . 'assets/images/loading.gif' ); ?>">
												</div>
												<span class="wp_cbb_qna_remove_row">
													<i class="fa fa-trash-o" aria-hidden="true"></i>
												</span>
											</td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div> 
						<input type="button" value="<?php esc_attr_e( ' + Add Q&A', 'wp-chatbot-builder' ); ?>" class="wp_cbb_add_qa_button button" id="wp_cbb_add_more_qa">
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="clear"></div>
