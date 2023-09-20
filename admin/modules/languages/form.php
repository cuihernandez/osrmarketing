<?php
$module_name = "languages";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

function decodeUnicodeEscapeSequences($matches) {
    return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
}


if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
    $edit = true;
    $get = $languages->get($_REQUEST['id']);
    if (!$get) {
        header("location:/admin/" . $module_name);
        die();
    }
}

function getTranslation($key) {
    global $languages;

    // Obtém a tradução para o idioma que está sendo editado
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
        $get = $languages->get($_REQUEST['id']);
        $translations_array = json_decode($get->translations, true);
        $value = $translations_array[$key] ?? '';
    }

    // Se não estiver editando ou se a tradução estiver vazia, pega a tradução padrão em inglês
    if (!isset($value) || $value == '') {
        $get = $languages->get(1);  // Obtém as traduções em inglês
        $translations_array = json_decode($get->translations, true);
        $value = $translations_array[$key] ?? '';
    }

    // Decodifica sequências de escape unicode
    $value = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
     $value = htmlspecialchars($value);

    return $value;
}
?>


      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Languages</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
			<a href="<?php echo $base_url; ?>/admin/languages" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/languages/action" method="post" novalidate enctype="multipart/form-data" id="form">

		<fieldset class="border rounded-2 p-3 mb-4 bg-light">
			<legend><h5>Translate fields with Google Cloud API</h5></legend>
			<div class="row align-middle">

				<div class="col-lg-12 col-md-12">
					<div class="input-group">
					  <select class="form-select" id="sourceLangSelect">
					  </select>
					  <select class="form-select" id="targetLangSelect">
					  	<option value="">Select a target language</option>
					  </select>

					  <button type="button" class="btn btn-primary" id="translateBtn"><i class="bi bi-translate"></i> Translate</button>
					</div>					
				</div>
			</div>
		</fieldset>

			<?php if(!isset($edit)){?>
			<div class="row">
				<div class="col-12">
				      <div class="alert alert-warning">
				        <h6 class="mb-0"><i class="bi bi-info-circle-fill"></i> Attention, you are adding a new language. By default, all fields are initialized in English. You can manually translate everything or use the Google API to do so (an API key needs to be configured in the settings menu).</h6>
				      </div>						
				</div>
			</div>
			<?php } ?>

			 <fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Basic information</h5></legend>



			  <div class="row">
			    <div class="col-lg-12">
					  <div class="row">
							<div class="col-lg-6">
								<label class="mb-2 col-12">
								  <span>Lang Name</span>
								  <input required name="lang_name" type="text" class="form-control" placeholder="Lang name" value="<?php if(isset($edit) && $edit){echo ($get->lang_name ?? '');} ?>">
								</label>
							</div>					  	
							<div class="col-lg-3">
								<label class="mb-2 col-12">
								  <span>Lang code</span>
								  <input required name="lang" type="text" class="form-control" placeholder="Lang code" value="<?php if(isset($edit) && $edit){echo ($get->lang ?? '');} ?>">
								</label>
							</div>
					   </div>
			    </div>		    
			</fieldset>

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Header</h5></legend>

				<div class="row">					
					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Company Name</span>
						  <input name="company_name" type="text" class="form-control" value="<?php echo getTranslation('company_name');?>">
						</label>
					</div>

					<div class="col-lg-2">
					    <label class="mb-2 col-12">
					      <span>Sign in label</span>
					      <input name="sign_in" type="text" class="form-control" value="<?php echo getTranslation('sign_in'); ?>">
					    </label>
					</div>

					<div class="col-lg-2">
					    <label class="mb-2 col-12">
					      <span>Sign up label</span>
					      <input name="sign_up" type="text" class="form-control" value="<?php echo getTranslation('sign_up'); ?>">
					    </label>
					</div>

					<div class="col-lg-2">
					    <label class="mb-2 col-12">
					      <span>My panel</span>
					      <input name="my_panel" type="text" class="form-control" value="<?php echo getTranslation('my_panel'); ?>">
					    </label>
					</div>

					<div class="col-lg-3">
					    <label class="mb-2 col-12">
					      <span>My credits</span>
					      <input name="my_credits" type="text" class="form-control" value="<?php echo getTranslation('my_credits');?>">
					    </label>
					</div>

				</div>

			</fieldset>


			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Footer</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Footer title</span>
						  <input name="footer_title" type="text" class="form-control" value="<?php echo getTranslation('footer_title');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Footer title col1</span>
						  <input name="footer_title_col1" type="text" class="form-control" value="<?php echo getTranslation('footer_title_col1');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Footer title col2</span>
						  <input name="footer_title_col2" type="text" class="form-control" value="<?php echo getTranslation('footer_title_col2');?>">
						</label>
					</div>

					<div class="col-lg-6">
						<label class="mb-2 col-12">
						  <span>Footer resume</span>
						  <input name="footer_resume" type="text" class="form-control" value="<?php echo getTranslation('footer_resume');?>">
						</label>
					</div>

					<div class="col-lg-6">
						<label class="mb-2 col-12">
						  <span>Back to Top Label</span>
						  <input name="back_to_top" type="text" class="form-control" value="<?php echo getTranslation('back_to_top');?>">
						</label>
					</div>					

				</div>
			</fieldset>				

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Homepage - Hero</h5></legend>
				<div class="row">
					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Main Title</span>
						  <input name="main_title" type="text" class="form-control" value="<?php echo getTranslation('main_title');?>">
						</label>
					</div>

					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Sub Title</span>
						  <input name="sub_title" type="text" class="form-control" value="<?php echo getTranslation('sub_title');?>">
						</label>
					</div>	

					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Button Text</span>
						  <input name="button_header_cta" type="text" class="form-control" value="<?php echo getTranslation('button_header_cta');?>">
						</label>
					</div>

				</div>
			</fieldset>

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Homepage - (index)</h5></legend>
				<div class="row">

					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Body Title CTA</span>
						  <input name="body_title_cta" type="text" class="form-control" value="<?php echo getTranslation('body_title_cta');?>">
						</label>
					</div>

					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Body Sub Title</span>
						  <input name="body_sub_title" type="text" class="form-control" value="<?php echo getTranslation('body_sub_title');?>">
						</label>
					</div>

					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Chat button text</span>
						  <input name="chat_now" type="text" class="form-control" value="<?php echo getTranslation('chat_now');?>">
						</label>
					</div>

					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Load more button</span>
						  <input name="ai_show_more_button" type="text" class="form-control" value="<?php echo getTranslation('ai_show_more_button');?>">
						</label>
					</div>	

					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Specialists by category</span>
						  <input name="category_home_label" type="text" class="form-control" value="<?php echo getTranslation('category_home_label');?>">
						</label>
					</div>					

				</div>
			</fieldset>


			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Chat page</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>You</span>
						  <input name="you" type="text" class="form-control" value="<?php echo getTranslation('you');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Title of the list of AIs</span>
						  <input name="chat_call_action1" type="text" class="form-control" value="<?php echo getTranslation('chat_call_action1');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Sub Title of the list of AIs</span>
						  <input name="chat_call_action2" type="text" class="form-control" value="<?php echo getTranslation('chat_call_action2');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Employees List</span>
						  <input name="btn_employees_list" type="text" class="form-control" value="<?php echo getTranslation('btn_employees_list');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button About</span>
						  <input name="btn_about" type="text" class="form-control" value="<?php echo getTranslation('btn_about');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Config</span>
						  <input name="btn_config" type="text" class="form-control" value="<?php echo getTranslation('btn_config');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button New Chat</span>
						  <input name="button_new_chat" type="text" class="form-control" value="<?php echo getTranslation('button_new_chat');?>">
						</label>
					</div>
					
					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Download Chat TXT</span>
						  <input name="button_download_chat" type="text" class="form-control" value="<?php echo getTranslation('button_download_chat');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Download Chat PDF</span>
						  <input name="button_download_chat_pdf" type="text" class="form-control" value="<?php echo getTranslation('button_download_chat_pdf');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Download Chat DOCX</span>
						  <input name="button_download_chat_docx" type="text" class="form-control" value="<?php echo getTranslation('button_download_chat_docx');?>">
						</label>
					</div>					

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Default label</span>
						  <input name="label_default" type="text" class="form-control" value="<?php echo getTranslation('label_default');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Label Display Chat Language Output</span>
						  <input name="label_display_chat_language_output" type="text" class="form-control" value="<?php echo getTranslation('label_display_chat_language_output');?>">
						</label>
					</div>


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Label Display Chat Tone</span>
						  <input name="label_display_chat_tone" type="text" class="form-control" value="<?php echo getTranslation('label_display_chat_tone');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Label Display Chat Writing Style</span>
						  <input name="label_display_chat_writing_style" type="text" class="form-control" value="<?php echo getTranslation('label_display_chat_writing_style');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Close (only shown on mobile)</span>
						  <input name="button_close" type="text" class="form-control" value="<?php echo getTranslation('button_close');?>">
						</label>
					</div>


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Send</span>
						  <input name="button_send" type="text" class="form-control" value="<?php echo getTranslation('button_send');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Stop chat</span>
						  <input name="button_cancel" type="text" class="form-control" value="<?php echo getTranslation('button_cancel');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Input Placeholder</span>
						  <input name="input_placeholder" type="text" class="form-control" value="<?php echo getTranslation('input_placeholder');?>">
						</label>
					</div>


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Wait</span>
						  <input name="wait" type="text" class="form-control" value="<?php echo getTranslation('wait');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Is Typing</span>
						  <input name="is_typing" type="text" class="form-control" value="<?php echo getTranslation('is_typing');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Badword Feedback</span>
						  <input name="badword_feedback" type="text" class="form-control" value="<?php echo getTranslation('badword_feedback');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Error Chat Minlength</span>
						  <input name="error_chat_minlength" type="text" class="form-control" value="<?php echo getTranslation('error_chat_minlength');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Error Chat Minlength Part 2</span>
						  <input name="error_chat_minlength_part2" type="text" class="form-control" value="<?php echo getTranslation('error_chat_minlength_part2');?>">
						</label>
					</div>


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Creating IA Image</span>
						  <input name="creating_ia_image" type="text" class="form-control" value="<?php echo getTranslation('creating_ia_image');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Creating IA Image Chat Instruction</span>
						  <input name="creating_ia_image_chat_instruction" type="text" class="form-control" value="<?php echo getTranslation('creating_ia_image_chat_instruction');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Expire Img Message</span>
						  <input name="expire_img_message" type="text" class="form-control" value="<?php echo getTranslation('expire_img_message');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Copy Code 1</span>
						  <input name="copy_code1" type="text" class="form-control" value="<?php echo getTranslation('copy_code1');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Copy Code 2</span>
						  <input name="copy_code2" type="text" class="form-control" value="<?php echo getTranslation('copy_code2');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Copy Text 1</span>
						  <input name="copy_text1" type="text" class="form-control" value="<?php echo getTranslation('copy_text1');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Copy Text 2</span>
						  <input name="copy_text2" type="text" class="form-control" value="<?php echo getTranslation('copy_text2');?>">
						</label>
					</div>


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Close Modal (About)</span>
						  <input name="button_close_modal" type="text" class="form-control" value="<?php echo getTranslation('button_close_modal');?>">
						</label>
					</div>					

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Header Title PDF</span>
						  <input name="header_title_pdf" type="text" class="form-control" value="<?php echo getTranslation('header_title_pdf');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Login prompt when downloading file</span>
						  <input name="login_prompt_downloading_file" type="text" class="form-control" value="<?php echo getTranslation('login_prompt_downloading_file');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Create an account to continue title</span>
						  <input name="create_account_to_continue_title" type="text" class="form-control" value="<?php echo getTranslation('create_account_to_continue_title');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Create an account to continue text</span>
						  <input name="create_account_to_continue_text" type="text" class="form-control" value="<?php echo getTranslation('create_account_to_continue_text');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Question suggestions title</span>
						  <input name="questions_suggestions_title" type="text" class="form-control" value="<?php echo getTranslation('questions_suggestions_title');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Suggestions (label button)</span>
						  <input name="btn_suggestions" type="text" class="form-control" value="<?php echo getTranslation('btn_suggestions');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Use suggestion (label button)</span>
						  <input name="btn_use_suggestion" type="text" class="form-control" value="<?php echo getTranslation('btn_use_suggestion');?>">
						</label>
					</div>


				</div>
			</fieldset>


			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Price Page</h5></legend>
				<div class="row">


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Our recharge plans (Title)</span>
						  <input name="price_page_title" type="text" class="form-control" value="<?php echo getTranslation('price_page_title');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Purchase</span>
						  <input name="price_page_btn_purchase" type="text" class="form-control" value="<?php echo getTranslation('price_page_btn_purchase');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Pay with stripe</span>
						  <input name="price_page_pay_stripe" type="text" class="form-control" value="<?php echo getTranslation('price_page_pay_stripe');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Pay with bank deposit</span>
						  <input name="price_page_pay_bank_deposit" type="text" class="form-control" value="<?php echo getTranslation('price_page_pay_bank_deposit');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Pay with Paypal</span>
						  <input name="price_page_pay_paypal" type="text" class="form-control" value="<?php echo getTranslation('price_page_pay_paypal');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Close payment method</span>
						  <input name="close_payment_method" type="text" class="form-control" value="<?php echo getTranslation('close_payment_method');?>">
						</label>
					</div>						

				</div>
			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Ai team Page</h5></legend>
				<div class="row">


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>AI team Title</span>
						  <input name="ai_team_title" type="text" class="form-control" value="<?php echo getTranslation('ai_team_title');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Select an option (Category label)</span>
						  <input name="category_select_option" type="text" class="form-control" value="<?php echo getTranslation('category_select_option');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Filter (Category Button)</span>
						  <input name="category_filter" type="text" class="form-control" value="<?php echo getTranslation('category_filter');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>No AI found for this category</span>
						  <input name="category_filter_no_results" type="text" class="form-control" value="<?php echo getTranslation('category_filter_no_results');?>">
						</label>
					</div>					

				</div>
			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>404 Page</h5></legend>
				<div class="row">


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>404 Message</span>
						  <input name="message_404" type="text" class="form-control" value="<?php echo getTranslation('message_404');?>">
						</label>
					</div>	

				</div>
			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Checkout complete</h5></legend>
				<div class="row">


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Checkout complete title</span>
						  <input name="checkout_complete_title" type="text" class="form-control" value="<?php echo getTranslation('checkout_complete_title');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Please wait, you are being redirected</span>
						  <input name="checkout_complete_redirect" type="text" class="form-control" value="<?php echo getTranslation('checkout_complete_redirect');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Loading</span>
						  <input name="checkout_complete_loading" type="text" class="form-control" value="<?php echo getTranslation('checkout_complete_loading');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Purchase not found</span>
						  <input name="checkout_complete_purchase_not_found" type="text" class="form-control" value="<?php echo getTranslation('checkout_complete_purchase_not_found');?>">
						</label>
					</div>	

				</div>
			</fieldset>

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Sign in page</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Welcome back!</span>
						  <input name="welcome_back" type="text" class="form-control" value="<?php echo getTranslation('welcome_back');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>I forgot my password</span>
						  <input name="i_forgot_password" type="text" class="form-control" value="<?php echo getTranslation('i_forgot_password');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Login button</span>
						  <input name="login_button" type="text" class="form-control" value="<?php echo getTranslation('login_button');?>">
						</label>
					</div>		

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Don't have an account? Sign up</span>
						  <input name="dont_account_sign_up" type="text" class="form-control" value="<?php echo getTranslation('dont_account_sign_up');?>">
						</label>
					</div>	

				</div>
			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Sign up page</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Get started – register now!</span>
						  <input name="get_started_message" type="text" class="form-control" value="<?php echo getTranslation('get_started_message');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>I agree to the terms and conditions.</span>
						  <input name="i_agree_terms" type="text" class="form-control" value="<?php echo getTranslation('i_agree_terms');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Read here</span>
						  <input name="read_here" type="text" class="form-control" value="<?php echo getTranslation('read_here');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Create account button</span>
						  <input name="btn_create_account" type="text" class="form-control" value="<?php echo getTranslation('btn_create_account');?>">
						</label>
					</div>		

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Already have an account? Sign in</span>
						  <input name="have_account_sign_in" type="text" class="form-control" value="<?php echo getTranslation('have_account_sign_in');?>">
						</label>
					</div>	

				</div>
			</fieldset>			

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Request account to continue</h5></legend>
				<div class="row">


					<div class="col-lg-12">
						<label class="mb-2 col-12">
						  <span>Create an account or login to continue...</span>
						  <input name="request_account_continue" type="text" class="form-control" value="<?php echo getTranslation('request_account_continue');?>">
						</label>
					</div>	

				</div>
			</fieldset>

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Back links</h5></legend>
				<div class="row">


					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Back to homepage</span>
						  <input name="back_to_homepage" type="text" class="form-control" value="<?php echo getTranslation('back_to_homepage');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Back to pricing</span>
						  <input name="back_to_pricing" type="text" class="form-control" value="<?php echo getTranslation('back_to_pricing');?>">
						</label>
					</div>		

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Back to login</span>
						  <input name="back_to_login" type="text" class="form-control" value="<?php echo getTranslation('back_to_login');?>">
						</label>
					</div>	

				</div>
			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Password Reset Page</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Enter the code</span>
						  <input name="pr_enter_the_code" type="text" class="form-control" value="<?php echo getTranslation('pr_enter_the_code');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Instructions if email exists</span>
						  <input name="pr_email_message" type="text" class="form-control" value="<?php echo getTranslation('pr_email_message');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Input code (label)</span>
						  <input name="pr_input_code" type="text" class="form-control" value="<?php echo getTranslation('pr_input_code');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Continue button</span>
						  <input name="pr_continue_button" type="text" class="form-control" value="<?php echo getTranslation('pr_continue_button');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Send new code</span>
						  <input name="pr_send_new_code" type="text" class="form-control" value="<?php echo getTranslation('pr_send_new_code');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Send recovery code</span>
						  <input name="pr_send_recovery_code" type="text" class="form-control" value="<?php echo getTranslation('pr_send_recovery_code');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Reset password title</span>
						  <input name="pr_reset_password_title" type="text" class="form-control" value="<?php echo getTranslation('pr_reset_password_title');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Reset password sub instructions</span>
						  <input name="pr_reset_password_instructions" type="text" class="form-control" value="<?php echo getTranslation('pr_reset_password_instructions');?>">
						</label>
					</div>

				</div>
			</fieldset>	


			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Customers Panel</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Welcome title</span>
						  <input name="welcome_title" type="text" class="form-control" value="<?php echo getTranslation('welcome_title');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Chats (label)</span>
						  <input name="chats_label" type="text" class="form-control" value="<?php echo getTranslation('chats_label');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>No Chats message</span>
						  <input name="no_chats_message" type="text" class="form-control" value="<?php echo getTranslation('no_chats_message');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>No Chats message call action</span>
						  <input name="no_chats_message_call_action" type="text" class="form-control" value="<?php echo getTranslation('no_chats_message_call_action');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Messages (label)</span>
						  <input name="messages_label" type="text" class="form-control" value="<?php echo getTranslation('messages_label');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>My Chats title</span>
						  <input name="my_chats_title" type="text" class="form-control" value="<?php echo getTranslation('my_chats_title');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button view chats</span>
						  <input name="btn_view_chats" type="text" class="form-control" value="<?php echo getTranslation('btn_view_chats');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button new chat</span>
						  <input name="btn_customer_new_chat" type="text" class="form-control" value="<?php echo getTranslation('btn_customer_new_chat');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Options</span>
						  <input name="btn_options" type="text" class="form-control" value="<?php echo getTranslation('btn_options');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button back</span>
						  <input name="btn_customer_back" type="text" class="form-control" value="<?php echo getTranslation('btn_customer_back');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Refresh Page</span>
						  <input name="btn_customer_refresh_page" type="text" class="form-control" value="<?php echo getTranslation('btn_customer_refresh_page');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Customer total chat part 1</span>
						  <input name="customer_total_chat_part1" type="text" class="form-control" value="<?php echo getTranslation('customer_total_chat_part1');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Customer total chat part 2</span>
						  <input name="customer_total_chat_part2" type="text" class="form-control" value="<?php echo getTranslation('customer_total_chat_part2');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Chat (label)</span>
						  <input name="chat_label_list" type="text" class="form-control" value="<?php echo getTranslation('chat_label_list');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button Chat now</span>
						  <input name="btn_customer_chat_now" type="text" class="form-control" value="<?php echo getTranslation('btn_customer_chat_now');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Last message</span>
						  <input name="last_message" type="text" class="form-control" value="<?php echo getTranslation('last_message');?>">
						</label>
					</div>					

				</div>
			</fieldset>

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Customers Panel - Sidebar menu</h5></legend>
				<div class="row">


					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Menu - My Chats</span>
						  <input name="menu_my_chats" type="text" class="form-control" value="<?php echo getTranslation('menu_my_chats');?>">
						</label>
					</div>						

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Menu - My account</span>
						  <input name="menu_my_account" type="text" class="form-control" value="<?php echo getTranslation('menu_my_account');?>">
						</label>
					</div>	

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Menu - My purchases</span>
						  <input name="menu_my_purchases" type="text" class="form-control" value="<?php echo getTranslation('menu_my_purchases');?>">
						</label>
					</div>						

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Menu - Logout</span>
						  <input name="menu_logout" type="text" class="form-control" value="<?php echo getTranslation('menu_logout');?>">
						</label>
					</div>

				</div>
			</fieldset>					

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Customers Panel - My Account</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>My Account (title)</span>
						  <input name="my_account_title" type="text" class="form-control" value="<?php echo getTranslation('my_account_title');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Your name label</span>
						  <input name="my_account_name_input" type="text" class="form-control" value="<?php echo getTranslation('my_account_name_input');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>E-mail adress label</span>
						  <input name="my_account_email" type="text" class="form-control" value="<?php echo getTranslation('my_account_email');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Password label</span>
						  <input name="my_account_password" type="text" class="form-control" value="<?php echo getTranslation('my_account_password');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button save</span>
						  <input name="my_account_btn_save" type="text" class="form-control" value="<?php echo getTranslation('my_account_btn_save');?>">
						</label>
					</div>			

				</div>
			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Customers Panel - My Purchases</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>My Purchases (title)</span>
						  <input name="my_purchases_title" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_title');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Purchase details (title)</span>
						  <input name="my_purchases_details_title" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_details_title');?>">
						</label>
					</div>					

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Package name</span>
						  <input name="my_purchases_package_name" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_package_name');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Credits</span>
						  <input name="my_purchases_credits" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_credits');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Price</span>
						  <input name="my_purchases_price" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_price');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Payment Method</span>
						  <input name="my_purchases_payment_method" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_payment_method');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Status</span>
						  <input name="my_purchases_status" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_status');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Button view more</span>
						  <input name="my_purchases_btn_view_more" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_btn_view_more');?>">
						</label>
					</div>			

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Stripe label</span>
						  <input name="my_purchases_stripe" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_stripe');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Paypal label</span>
						  <input name="my_purchases_paypal" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_paypal');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Bank deposit label</span>
						  <input name="my_purchases_bank_deposit" type="text" class="form-control" value="<?php echo getTranslation('my_purchases_bank_deposit');?>">
						</label>
					</div>			

				</div>
			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Customers Panel - Payment status</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Payment Refused</span>
						  <input name="card_declined" type="text" class="form-control" value="<?php echo getTranslation('card_declined');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Expired Card</span>
						  <input name="expired_card" type="text" class="form-control" value="<?php echo getTranslation('expired_card');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Incorrect CVC</span>
						  <input name="incorrect_cvc" type="text" class="form-control" value="<?php echo getTranslation('incorrect_cvc');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Processing error</span>
						  <input name="processing_error" type="text" class="form-control" value="<?php echo getTranslation('processing_error');?>">
						</label>
					</div>				

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Incorrect Number</span>
						  <input name="incorrect_number" type="text" class="form-control" value="<?php echo getTranslation('incorrect_number');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Processing</span>
						  <input name="processing" type="text" class="form-control" value="<?php echo getTranslation('processing');?>">
						</label>
					</div>		

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Abandoned Checkout</span>
						  <input name="abandoned_checkout" type="text" class="form-control" value="<?php echo getTranslation('abandoned_checkout');?>">
						</label>
					</div>
					
					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Awaiting payment</span>
						  <input name="awaiting_payment" type="text" class="form-control" value="<?php echo getTranslation('awaiting_payment');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Payment accept</span>
						  <input name="succeeded" type="text" class="form-control" value="<?php echo getTranslation('succeeded');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Message - Your payment has been approved.</span>
						  <input name="message_payment_approved" type="text" class="form-control" value="<?php echo getTranslation('message_payment_approved');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Message - Your payment was declined.</span>
						  <input name="message_payment_declined" type="text" class="form-control" value="<?php echo getTranslation('message_payment_declined');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Message - Your payment has been refunded.</span>
						  <input name="message_payment_refunded" type="text" class="form-control" value="<?php echo getTranslation('message_payment_refunded');?>">
						</label>
					</div>	

				</div>
			</fieldset>			

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Alert messages or system errors</h5></legend>
				<div class="row">

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Success. Follow the deposit instructions.</span>
						  <input name="order_successfully_deposit" type="text" class="form-control" value="<?php echo getTranslation('order_successfully_deposit');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Login successful, welcome!</span>
						  <input name="login_success_message" type="text" class="form-control" value="<?php echo getTranslation('login_success_message');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Data updated successfully</span>
						  <input name="data_updated_success" type="text" class="form-control" value="<?php echo getTranslation('data_updated_success');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Invalid data, try again</span>
						  <input name="error_invalid_data" type="text" class="form-control" value="<?php echo getTranslation('error_invalid_data');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>invalid recaptcha, try again</span>
						  <input name="error_invalid_recaptcha" type="text" class="form-control" value="<?php echo getTranslation('error_invalid_recaptcha');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Duplicate email on registration</span>
						  <input name="error_duplicate_email" type="text" class="form-control" value="<?php echo getTranslation('error_duplicate_email');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>System error when registering (Sign up)</span>
						  <input name="error_sign_up" type="text" class="form-control" value="<?php echo getTranslation('error_sign_up');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Error updating record</span>
						  <input name="error_update_record" type="text" class="form-control" value="<?php echo getTranslation('error_update_record');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Invalid email</span>
						  <input name="invalid_email" type="text" class="form-control" value="<?php echo getTranslation('invalid_email');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Welcome back (password recovery)</span>
						  <input name="welcome_back_password_recovery" type="text" class="form-control" value="<?php echo getTranslation('welcome_back_password_recovery');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Incorrect code, please try again</span>
						  <input name="incorrect_code" type="text" class="form-control" value="<?php echo getTranslation('incorrect_code');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>We were unable to recover your access</span>
						  <input name="unable_recover_acess" type="text" class="form-control" value="<?php echo getTranslation('unable_recover_acess');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Invalid Data</span>
						  <input name="invalid_data" type="text" class="form-control" value="<?php echo getTranslation('invalid_data');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Disabled/banned user message</span>
						  <input name="customer_disable_message" type="text" class="form-control" value="<?php echo getTranslation('customer_disable_message');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Your credits have run out.</span>
						  <input name="credits_run_out" type="text" class="form-control" value="<?php echo getTranslation('credits_run_out');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Password must have at least 6 characters.</span>
						  <input name="password_have_6_char" type="text" class="form-control" value="<?php echo getTranslation('password_have_6_char');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>The password entered is strong</span>
						  <input name="password_entered_strong" type="text" class="form-control" value="<?php echo getTranslation('password_entered_strong');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>The password entered is medium</span>
						  <input name="password_entered_medium" type="text" class="form-control" value="<?php echo getTranslation('password_entered_medium');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>The password entered is weak</span>
						  <input name="password_entered_weak" type="text" class="form-control" value="<?php echo getTranslation('password_entered_weak');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Loading</span>
						  <input name="loading" type="text" class="form-control" value="<?php echo getTranslation('loading');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Package no longer exists or has been removed</span>
						  <input name="package_no_exists" type="text" class="form-control" value="<?php echo getTranslation('package_no_exists');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Error when checking out, contact an administrator</span>
						  <input name="error_checking_out" type="text" class="form-control" value="<?php echo getTranslation('error_checking_out');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>The payment method has not been defined</span>
						  <input name="error_payment_not_defined" type="text" class="form-control" value="<?php echo getTranslation('error_payment_not_defined');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>DALL-E requires login message</span>
						  <input name="dalle_require_login" type="text" class="form-control" value="<?php echo getTranslation('dalle_require_login');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Maintenance admin message</span>
						  <input name="maintenance_mode" type="text" class="form-control" value="<?php echo getTranslation('maintenance_mode');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Chat embedding not allowed message</span>
						  <input name="chat_embedding_not_allowed_message" type="text" class="form-control" value="<?php echo getTranslation('chat_embedding_not_allowed_message');?>">
						</label>
					</div>


				</div>
			</fieldset>

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Customer confirm e-mail</h5></legend>
				<div class="row">
					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Confirm your email</span>
						  <input name="confirm_email_title" type="text" class="form-control" value="<?php echo getTranslation('confirm_email_title');?>">
						</label>
					</div>

					<div class="col-lg-8">
						<label class="mb-2 col-12">
						  <span>Confirm email message</span>
						  <input name="confirm_email_message" type="text" class="form-control" value="<?php echo getTranslation('confirm_email_message');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Unable to verify your email</span>
						  <input name="confirm_email_unable_verify" type="text" class="form-control" value="<?php echo getTranslation('confirm_email_unable_verify');?>">
						</label>
					</div>

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Send email again</span>
						  <input name="confirm_email_resend_button" type="text" class="form-control" value="<?php echo getTranslation('confirm_email_resend_button');?>">
						</label>
					</div>	

					<div class="col-lg-4">
						<label class="mb-2 col-12">
						  <span>Please wait 30 seconds before resending the email</span>
						  <input name="confirm_email_resend_message" type="text" class="form-control" value="<?php echo getTranslation('confirm_email_resend_message');?>">
						</label>
					</div>					
				</div>
			</fieldset>

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Blog</h5></legend>

				<div class="row">

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Blog title</span>
						  <input name="blog_title" type="text" class="form-control" value="<?php echo getTranslation('blog_title');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Blog subtitle</span>
						  <input name="blog_sub_title" type="text" class="form-control" value="<?php echo getTranslation('blog_sub_title');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Read more</span>
						  <input name="blog_read_more" type="text" class="form-control" value="<?php echo getTranslation('blog_read_more');?>">
						</label>
					</div>	

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>All tags (Post detail page)</span>
						  <input name="blog_all_tags_label" type="text" class="form-control" value="<?php echo getTranslation('blog_all_tags_label');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>No posts found with this tag</span>
						  <input name="no_post_found_tag" type="text" class="form-control" value="<?php echo getTranslation('no_post_found_tag');?>">
						</label>
					</div>										

				</div>

			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Share chat</h5></legend>

				<div class="row">

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Share</span>
						  <input name="share" type="text" class="form-control" value="<?php echo getTranslation('share');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Conversation between</span>
						  <input name="share_chat_conversation_between" type="text" class="form-control" value="<?php echo getTranslation('share_chat_conversation_between');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>And</span>
						  <input name="share_chat_and" type="text" class="form-control" value="<?php echo getTranslation('share_chat_and');?>">
						</label>
					</div>	

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>To use this function you need to be logged in.</span>
						  <input name="share_chat_message" type="text" class="form-control" value="<?php echo getTranslation('share_chat_message');?>">
						</label>
					</div>									

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>The url was successfully copied to your clipboard</span>
						  <input name="share_chat_copy_clipboard" type="text" class="form-control" value="<?php echo getTranslation('share_chat_copy_clipboard');?>">
						</label>
					</div>									

				</div>

			</fieldset>	

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Cookie Message</h5></legend>

				<div class="row">

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Cookie Message</span>
						  <input name="cookie_message" type="text" class="form-control" value="<?php echo getTranslation('cookie_message');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Cookie accept button</span>
						  <input name="cookie_accept_btn" type="text" class="form-control" value="<?php echo getTranslation('cookie_accept_btn');?>">
						</label>
					</div>	

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>Cookie decline button</span>
						  <input name="cookie_decline_btn" type="text" class="form-control" value="<?php echo getTranslation('cookie_decline_btn');?>">
						</label>
					</div>	
				</div>

			</fieldset>


			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>VIP</h5></legend>

				<div class="row">

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>VIP (label)</span>
						  <input name="vip_label" type="text" class="form-control" value="<?php echo getTranslation('vip_label');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>VIP - Upgrade message</span>
						  <input name="vip_upgrade_message" type="text" class="form-control" value="<?php echo getTranslation('vip_upgrade_message');?>">
						</label>
					</div>

					<div class="col-lg-3">
						<label class="mb-2 col-12">
						  <span>VIP - Check the plans (Button)</span>
						  <input name="vip_check_plans_btn" type="text" class="form-control" value="<?php echo getTranslation('vip_check_plans_btn');?>">
						</label>
					</div>					
				</div>

			</fieldset>										
		

<?php
require_once(__DIR__."/../../inc/default-form-footer.php");
require_once(__DIR__."/../../inc/footer.php");
?>