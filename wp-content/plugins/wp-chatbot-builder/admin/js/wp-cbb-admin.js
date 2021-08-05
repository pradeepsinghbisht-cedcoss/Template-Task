	(function( $ ) {

		'use strict';



		/**

		* All of the code for your admin-facing JavaScript source

		* should reside in this file.

		*

		* Note: It has been assumed you will write jQuery code here, so the

		* $ function reference has been prepared for usage within the scope

		* of this function.

		*

		* This enables you to define handlers, for when the DOM is ready:

		*

		* $(function() {

		*

		* });

		*

		* When the window is loaded:

		*

		* $( window ).load(function() {

		*

		* });

		*

		* ...and/or other possibilities.

		*

		* Ideally, it is not considered best practise to attach more than a

		* single DOM-ready or window-load handler for a particular page.

		* Although scripts in the WordPress core, Plugins and Themes may be

		* practising this, we should strive to set a better example in our own work.

		*/

		jQuery(document).ready(function($) { 	

			//Add color Picker.

				$('input#wp_cbb_color_picker').wpColorPicker();



			$('#wp_cbb_display_select').change( function(){

				var select_option = $(this).val();

				if( select_option == 'eve'){

					$('#wp_cbb_display_pages_eve').removeClass('hidden');

					$('#wp_cbb_display_pages_except').addClass('hidden');

					$('#wp_cbb_display_pages_only').addClass('hidden');

					$('#wp_cbb_display_pages_home').addClass('hidden');

				}

				else if( select_option == 'eve_except'){

					$('#wp_cbb_display_pages_eve').addClass('hidden');

					$('#wp_cbb_display_pages_except').removeClass('hidden');

					$('#wp_cbb_display_pages_only').addClass('hidden');

					$('#wp_cbb_display_pages_home').addClass('hidden');

				}

				else if( select_option == 'only_these'){

					$('#wp_cbb_display_pages_eve').addClass('hidden');

					$('#wp_cbb_display_pages_except').addClass('hidden');

					$('#wp_cbb_display_pages_only').removeClass('hidden');

					$('#wp_cbb_display_pages_home').addClass('hidden');

				}

				else if( select_option == 'only_home'){

					$('#wp_cbb_display_pages_eve').addClass('hidden');

					$('#wp_cbb_display_pages_except').addClass('hidden');

					$('#wp_cbb_display_pages_only').addClass('hidden');

					$('#wp_cbb_display_pages_home').removeClass('hidden');

				}

			});



			$('#wp_cbb_display_pages_only_select').select2();

			$('#wp_cbb_display_pages_except_select').select2();

			$(document).on("click", ".wp_cbb_setting_collapse_btn", function() {
					if ($(this).find('i').hasClass('fa-angle-up')) {
						$(this).find('i').removeClass('fa-angle-up');
						$(this).find('i').addClass('fa-angle-down');
					} else {
						$(this).find('i').removeClass('fa-angle-down');
						$(this).find('i').addClass('fa-angle-up');
					}
					$(".wp_cbb_setting_collapse_text").slideToggle("slow");
			});

			// CHATBOT ADD QUESTION TERMS

			$(document).on("click",".wp_cbb_add_more_ques_terms", function() {

				var questn_term =  $(this).prev('textarea').val();

				if( questn_term != null && questn_term != ""){

					let fieldsetId  = $(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_quest_term").last().attr("data-index");

							fieldsetId = fieldsetId?fieldsetId.replace(/[^0-9]/gi, ''):0;

							const count = Number(fieldsetId) + 1;

				

					const length = $(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_quest_term").length;

							if( length === 25 ) {

								return;

							}

							const mainId = $(this).parents(".wp_cbb_qa_wrap").attr("id");

							

							let newAnswer = '<div class="wp_cbb_quest_term_wrap">'+

							'<span class="wp_cbb_quest_term" data-index="'+count+'">'+questn_term+'</span>'+

							'<span class="wp_cbb_qa__bin" data-index="'+count+'"><i class="fa fa-times" aria-hidden="true"></i></span></div>';

							let newInput = '<input type="hidden" name="wp_cbb_['+mainId+'][questions][]" value="'+questn_term+'" id="wp_cbb_qa_'+mainId+'_'+count+'">';

							$(this).prev('textarea').val('');

							$(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_questions_wrapper").append(newAnswer);

							$(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_questions_hidden_wrapper").append(newInput);

				}

			});



			// CHATBOT QUESTION TERM DELETION

			$(document).on("click",".wp_cbb_qa__bin", function() {

						var dataIndex = $(this).attr("data-index");

						var mainId = $(this).parents(".wp_cbb_qa_wrap").attr("id");

						var inputs = $(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_questions_hidden_wrapper").find("input");

						if(inputs.length === 1) {

							if($(this).find('.tooltip').length==0){

									$('<p class="tooltip"></p>')

										.text(" At least 1 keyword is required for each Q&A")

										.appendTo($(this))

										.fadeIn('fast');

							}

							return;

						}

						$(this).parents(".wp_cbb_qa_new_wrapper").find("#wp_cbb_qa_"+mainId+'_'+dataIndex).remove();

						$(this).parent(".wp_cbb_quest_term_wrap").remove();

				});



				// CHATBOT ADD ANSWERS TO QUESTIONS

				$(document).on("click",".wp_cbb_add_more_answer_terms", function() {

				var answer_terms =  $(this).prev('textarea').val();

				if( answer_terms != null && answer_terms != ""){

					let fieldsetId  = $(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_qtn_answer").last().attr("data-index");

							fieldsetId = fieldsetId?fieldsetId.replace(/[^0-9]/gi, ''):0;

							const count = Number(fieldsetId) + 1;

					const length = $(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_qtn_answer").length;

							if( length === 25 ) {

								return;

							}

							const mainId = $(this).parents(".wp_cbb_qa_wrap").attr("id");

							let newAnswer = '<div class="wp_cbb_qtn_answer_wrap">'+

							'<textarea class="wp_cbb_qtn_answer" data-index="'+count+'">'+answer_terms+'</textarea>'+

							'<span class="wp_cbb_answer__bin" data-index="'+count+'"><i class="fa fa-times" aria-hidden="true"></i></span></div>';

							let newInput = '<input type="hidden" name="wp_cbb_['+mainId+'][answers][]" value="'+answer_terms+'" id="wp_cbb_answer_'+mainId+'_'+count+'">';

							$(this).prev('textarea').val('');

							$(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_response_wrapper").append(newAnswer);

							$(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_response_hidden_wrapper").append(newInput);

					}

			});



			// CHATBOT ANSWER DELETION

			$(document).on("click",".wp_cbb_answer__bin", function() {

						var dataIndex = $(this).attr("data-index");

						var mainId = $(this).parents(".wp_cbb_qa_wrap").attr("id");

						var inputs = $(this).parents(".wp_cbb_qa_new_wrapper").find(".wp_cbb_response_hidden_wrapper").find("input");

						if(inputs.length === 1) {

							if($(this).find('.tooltip').length==0){

									$('<p class="tooltip"></p>')

										.text(" At least 1 answer is required for each Q&A")

										.appendTo($('.wp_cbb_qtn_answer_wrap'))

										.fadeIn('fast');

							}

							return;

					}

						$(this).parents(".wp_cbb_qa_new_wrapper").find("#wp_cbb_answer_"+mainId+'_'+dataIndex).remove();

						$(this).parent(".wp_cbb_qtn_answer_wrap").remove();

				});



			// ADD QUESTION ANSWER ROW ON ADD QUESTION CLICK EVENT

				$(document).on("click", ".wp_cbb_add_qa_button", function(){

					let rowCount  = $(document).find(".wp_cbb_qa_wrap").last().attr("id");

					let mainId = Number(rowCount) + 1

				var tbody_length = $( '.wp_cbb_qa_tbody > tr' ).length;

				var wp_cbb_url = wp_cbb_handle.wp_cbb_url;

				var new_row = '<tr class="wp_cbb_qa_wrap" id="'+mainId+'" data-knowledgeId=""><td><textarea class="wp_cbb_qa_input_questions" placeholder="e.g. Order, Refund etc."></textarea><i class="fa fa-plus wp_cbb_add_more_ques_terms" aria-hidden="true"></i><div class="wp_cbb_questions_wrapper"></div><div class="wp_cbb_questions_hidden_wrapper"></div></td><td><textarea class="wp_cbb_qa_input_answers" placeholder="Enter your answer here"></textarea><i class="fa fa-plus wp_cbb_add_more_answer_terms" aria-hidden="true"></i><div class="wp_cbb_response_wrapper"></div><div class="wp_cbb_response_hidden_wrapper"></div></td><td class="wp_cbb_remove_qa_row"><input type="button" name="wp_cbb_qna_save" class="button wp_cbb_qna_save" value="Update"><div style="display: none;" class="wp-cbb-loader" id="wp_cbb_qna_save_loader"><img src="'+wp_cbb_url+'assets/images/loading.gif"></div><span class="wp_cbb_qna_remove_row"><i class="fa fa-trash-o" aria-hidden="true"></i></span></td></tr>';


				if ( tbody_length == 2 ) {

					$( '.wp_cbb_qna_remove_row' ).each(

						function() {

							$( this ).show();

						}

					);

				}

				$( '.wp_cbb_qa_tbody' ).append( new_row );

				});



				// HIDE DELETE ICON WHEN ONLY ONE Q/A ROW 

				if ($( '.wp_cbb_qa_tbody > tr' ).length == 2) {

				$( '.wp_cbb_qna_remove_row' ).each(

					function() {

						$( this ).hide();

					}

				);

			}



			// CONNECT BOT WITH FB PAGE

			$(document).on( "click",".wp_cbb_connect_fb" ,function(e){

				e.preventDefault();

				var page_id = $(this).data('id');

				$(this).find( '.wp_cbb_connect_fb_page' ).css('display','inline-block');

				$(this).siblings( '.wp-cbb-loader' ).css('display','inline-block');

				var thisclass = this;

				var data = {

					action:'wp_cbb_connect_fb_page',

					page_id: page_id,

					cb_nonce:wp_cbb_handle.wp_cbb_nonce,

				};

				$.ajax(

					{

						url:wp_cbb_handle.ajaxurl,

						type:'POST',

						data:data,

						dataType :'json',

						success:function(response){

							$( '#wp_cbb_connect_fb_page' ).css('display','none');

							$(thisclass).siblings( '.wp-cbb-loader' ).css('display','none');

							if (response.status == true) {

								window.location.reload();

							} else {

								var html = '<span>' + response.message + '</span>';

								$( ".wp_cbb_notice_wrap" ).html( html );
								$( ".wp_cbb_notice_wrap" ).addClass( 'wp_cbb_notice_wrap_active' );
								$( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );

							}

							

						}

					}

				);

			});



			// SAVE ENABLE PLUGIN DATA SETTING

			$(document).on( "click","#wp_cbb_enable_plugin_save" ,function(e){

				e.preventDefault();

				var check_enable_box = $( '#wp_cbb_enable_plugin' ).prop( "checked" );

				$( '#wp_cbb_enable_plugin_save_loader' ).css('display','inline-block');

				var data = {

					action:'wp_cbb_enable_plugin_save',

					cb_nonce:wp_cbb_handle.wp_cbb_nonce,

					check_enable_box:check_enable_box,

				};

				$.ajax(

					{

						url:wp_cbb_handle.ajaxurl,

						type:'POST',

						data:data,

						dataType :'json',

						success:function(response){

							$( '#wp_cbb_enable_plugin_save_loader' ).css('display','none');						

						}

					}

				);

			});



			// SAVE WELCOME MESSAGE DATA SETTING

			$(document).on( "click","#wp_cbb_welcome_message_save" ,function(e){

				e.preventDefault();

				var welcome_message = $( '#wp_cbb_welcome_message' ).val();

				var wlcm_msg_id = $( '#wp_cbb_wlcm_msg_id' ).val();

				$( '#wp_cbb_welcome_message_save_loader' ).css('display','inline-block');

				var data = {

					action:'wp_cbb_welcome_message_save',

					cb_nonce:wp_cbb_handle.wp_cbb_nonce,

					welcome_message:welcome_message,

					welcom_message_id:wlcm_msg_id,

				};

				$.ajax(

					{

						url:wp_cbb_handle.ajaxurl,

						type:'POST',

						data:data,

						dataType :'json',

						success:function(response){

							$( '#wp_cbb_welcome_message_save_loader' ).css('display','none');						

						}

					}

				);

			});



			// SAVE DEFAULT MESSAGE DATA SETTING

			$(document).on( "click","#wp_cbb_default_message_save" ,function(e){

				e.preventDefault();

				var default_message = $( '#wp_cbb_default_message' ).val();

				var default_msg_id = $( '#wp_cbb_default_msg_id' ).val();

				$( '#wp_cbb_default_message_save_loader' ).css('display','inline-block');

				var data = {

					action:'wp_cbb_default_message_save',

					cb_nonce:wp_cbb_handle.wp_cbb_nonce,

					default_message:default_message,

					default_msg_id:default_msg_id,

				};

				$.ajax(

					{

						url:wp_cbb_handle.ajaxurl,

						type:'POST',

						data:data,

						dataType :'json',

						success:function(response){

							$( '#wp_cbb_default_message_save_loader' ).css('display','none');						

						}

					}

				);

			});



			// SAVE QUESTION AND ANSWER DATASET SETTING

			$(document).on( "click",".wp_cbb_qna_save" ,function(e){

				e.preventDefault();

				var question_set = [];

				var answer_set = [];

				var knowledge_id = $(this).parents(".wp_cbb_qa_wrap").attr("data-knowledgeId");

						var questionTerms = $(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_questions_hidden_wrapper").find("input");

				$( questionTerms ).each(

					function() {

						question_set.push( $( this ).val() );

					}

				);

				var answerTerms = $(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_response_hidden_wrapper").find("input");

				$( answerTerms ).each(

					function() {

						answer_set.push( $( this ).val() );

					}

				);

				if( question_set.length == 0 || answer_set.length == 0 )

				return;

				var data = {

					action:'wp_cbb_qna_set_save',

					cb_nonce:wp_cbb_handle.wp_cbb_nonce,

					knowledge_id:knowledge_id,

					question_set:question_set,

					answer_set:answer_set,

				};

				$(this).siblings('.wp_cbb_qna_remove_row').css('display','none');

				$(this).siblings('.wp-cbb-loader').css('display','inline-block');

				var thisclass = this;

				$.ajax(

					{

						url:wp_cbb_handle.ajaxurl,

						type:'POST',

						data:data,

						dataType :'json',

						success:function(response){

							$(thisclass).siblings('.wp-cbb-loader').css('display','none');

							window.location.reload();				

						}

					}

				);

			});



			// DELETE THE QUESTION ANSWER ROW ON DELETE BUTTON CLICK EVENT

			$(document).on("click", ".wp_cbb_qna_remove_row", function(){

				var res = confirm( "Are you sure ! want to delete the entire row details ? " );

				if (res == true) {

					$( this ).closest( 'tr' ).remove();

						var tbody_length = $( '.wp_cbb_qa_tbody > tr' ).length;

						if( tbody_length == 2 ){

							$( '.wp_cbb_qna_remove_row' ).each(

							function() {

								$( this ).hide();

							}

						);

						}

						var knowledge_id = $(this).parents(".wp_cbb_qa_wrap").attr("data-knowledgeId");

						var data = {

						action:'wp_cbb_remove_qna_set',

						cb_nonce:wp_cbb_handle.wp_cbb_nonce,

						knowledge_id:knowledge_id,

					};

					$(this).siblings('.wp-cbb-loader').css('display','inline-block');

					var thisclass = this;

					$.ajax(

						{

							url:wp_cbb_handle.ajaxurl,

							type:'POST',

							data:data,

							dataType :'json',

							success:function(response){
								
								$(thisclass).siblings('.wp-cbb-loader').css('display','none');

							}

						}

					);

				} else {

					return false;

				}    		

			});

			$(document).on("keyup",".wp_cbb_qtn_answer", function(){
				var edited_text = $(this).val();
				var edited_index = $(this).attr("data-index");
				const mainId = $(this).parents(".wp_cbb_qa_wrap").attr("id");
				$(this).parents(".wp_cbb_qa_wrap").find(".wp_cbb_response_hidden_wrapper").find("#wp_cbb_answer_"+mainId+"_"+edited_index).val(edited_text);
			});

		});

	})( jQuery );

