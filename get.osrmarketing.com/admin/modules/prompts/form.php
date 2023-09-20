<?php
$module_name = "prompts";
$use_save_absolute = true;
$use_sortable = true;
$use_codemirror = true;
$use_select2 = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
$getCategories = $categories->getList();
$categoriesArray = array();
$creditsPacksArray = array();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $prompts->get($_REQUEST['id']);
	$get = array_map(function ($value) {
	    return is_string($value) ? stripslashes($value) : $value;
	}, (array) $get);
	$get = (object) $get;

    
    $getCategoriesByIdPrompt = $prompts_categories->getListByIdPrompt($get->id);
    foreach ($getCategoriesByIdPrompt as $showCategories) {
        $categoriesArray[] = $showCategories->id_category;
    }
    
    if (!$get) {
        header("location:".$base_url."/admin/".$module_name);
        die();
    }
}

$getPromptsOutput = $prompts_output->getList();
$getPromptsTone = $prompts_tone->getList();
$getPromptsWriting = $prompts_writing->getList();

function generateSuggestionsField($suggestions = null) {
	$valueAttribute = $suggestions ? ' value="' . htmlspecialchars($suggestions) . '"' : '';
	echo '
	<div class="input-group mb-3 descriptionField">
	<input type="text" name="suggestions[]" class="form-control" placeholder="Description"' . $valueAttribute . '>
	<button type="button" class="btn btn-success addDescription"><i class="bi bi-plus-circle"></i> Add new</button>
	<button type="button" class="btn btn-danger removeDescription"><i class="bi bi-trash"></i> Remove</button>
	</div>
	';
}
require_once(__DIR__."/../../inc/header.php");
include(__DIR__."/models-info.php");
include(__DIR__."/system-voices-info.php");
?>


	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Prompts</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<?php if(isset($edit) && $edit){?>
			<a href="<?php echo $base_url."/chat/".$get->slug; ?>" target="_blank" class="btn btn-primary me-2"><i class="bi bi-box-arrow-up-right"></i> View chat</a>
			<?php } ?>
			<a href="<?php echo $base_url; ?>/admin/prompts" class="btn btn-danger btn-primary">Cancel</a>
		</div>
	</div>


	<form action="/admin/prompts/action" method="post" novalidate enctype="multipart/form-data" id="form">

		<?php if(isset($edit) && $edit){?>
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
		<?php } ?>

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5>Basic information</h5></legend>

			<div class="row">
				<div class="col-md-4 col-lg-3">

					<div class="mb-3">
						<div class="wrapper-image-preview-form">
							<input name="image" type="file" class="form-control" id="image" accept="image/*" onchange="loadPreviewImage(event, 'imagePreview')">
							<img class="img-fluid" id="imagePreview" src="<?php echo !empty($get->image) ? $base_url . '/public_uploads/' . $get->image : '#'; ?>" onerror="this.src='https://placehold.co/640x700'">
						</div>
					</div>	

				</div>
				<div class="col-md-8 col-lg-9">

					<div class="row">

						<div class="col-md-12">
							<div class="form-check form-switch mb-3 custom-switch">
								<input class="form-check-input" type="checkbox" id="floatingDisplayStatus" 
								<?php if (!isset($edit) || ($edit && ($get->status ?? 0) == 1)) { echo 'checked'; } ?>
								onchange="updateSwitchValue('floatingDisplayStatus', 'hiddenDisplayStatus')">
								<input type="hidden" name="status" id="hiddenDisplayStatus" 
								value="<?php echo (!isset($edit) || ($edit && $get->status == 1)) ? 1 : 0; ?>">
								<label class="form-check-label" for="floatingDisplayStatus">Enable/Disabled in website</label>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-floating mb-3">
								<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Name of the employee that will be displayed."></span>
								<input name="name" type="text" class="form-control" id="floatingInputName" placeholder="AI Name" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
								<label for="floatingInputName">AI Name</label>
							</div>	   			    	
						</div>					    

						<div class="col-md-6">
							<div class="form-floating mb-3">
								<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="In which area is the AI an expert? (Example: Finance, Marketing, etc.)"></span>
								<input name="expert" type="text" class="form-control" id="floatingExpert" placeholder="Expert" value="<?php if(isset($edit) && $edit){echo ($get->expert ?? '');} ?>" required>
								<label for="floatingExpert">Expert in</label>
							</div>		             			    	
						</div>

						<div class="col-md-6">
							<div class="form-floating mb-3">
								<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="The slug is the field that is inserted in the URL to identify a specific page, such as /chat/ai-name, where 'ai-name' is the slug that identifies the corresponding AI page."></span>
								<input name="slug" type="text" class="form-control" id="floatingInputSlug" placeholder="Slug" value="<?php if(isset($edit) && $edit){echo ($get->slug ?? '');} ?>" required>
								<label for="floatingInputSlug">Slug (URL)</label>
							</div>		             			    	
						</div>

					</div>

					<div class="col-md-3">
						<div class="form-check form-switch mb-3 custom-switch">
							<input class="form-check-input" type="checkbox" id="floatingDisplayDescription" <?php if (isset($edit) && $edit && ($get->display_description ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayDescription', 'hiddenDisplayDescription')">
							<input type="hidden" name="display_description" id="hiddenDisplayDescription" value="<?php echo isset($edit) && $edit ? $get->display_description : 0; ?>">
							<label class="form-check-label" for="floatingDisplayDescription">Show description?</label>
						</div>
					</div>

					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="First-person description introducing the AI (This data is displayed in a top icon in the chat conversation)"></span>
						<textarea name="description" class="form-control text-area-custom-h" id="floatingDescription" placeholder="Description"><?php if(isset($edit) && $edit){echo ($get->description ?? '');} ?></textarea>
						<label for="floatingDescription">Description</label>
					</div>

					<div class="col-md-3">
						<div class="form-check form-switch mb-3 custom-switch">
							<input class="form-check-input" type="checkbox" id="floatingDisplayWelcomeMessage" <?php if (isset($edit) && $edit && ($get->display_welcome_message ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayWelcomeMessage', 'hiddenDisplayWelcomeMessage')">
							<input type="hidden" name="display_welcome_message" id="hiddenDisplayWelcomeMessage" value="<?php echo isset($edit) && $edit ? $get->display_welcome_message : 0; ?>">
							<label class="form-check-label" for="floatingDisplayWelcomeMessage">Display welcome message?</label>
						</div>
					</div>

					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Welcome message, it is the first message that the AI will send when the user opens their chat."></span>
						<textarea name="welcome_message" class="form-control text-area-custom-h" id="flexCheckDefault" placeholder="Description"><?php if(isset($edit) && $edit){echo ($get->welcome_message ?? '');} ?></textarea>
						<label for="floatingWelcomeMessage">Welcome Message</label>
					</div>

				</div>		    

			</fieldset>


		<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Categories</h5></legend>

				<div class="form-floating mb-3">
					<select class="form-select select2" id="multiple-select-field" name="categories[]" data-placeholder="Categories" multiple>
						<?php foreach ($getCategories as $showCategories) {?>
							<option 
							value="<?php echo $showCategories->id; ?>" 
							<?php echo in_array($showCategories->id, $categoriesArray) ? 'selected' : ''; ?>
							>
							<?php echo $showCategories->name; ?><?php echo $showCategories->status == '0' ? ' (Disabled)' : ''; ?>
						</option>
					<?php } ?>
				</select>
			</div>	
			
		</fieldset>

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><span data-feather="cpu"></span> AI training</h5></legend>
                <?php if($config->demo_mode){?>
                  <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the content is not shown in demo mode.</div></div>
                <?php }else{ ?>
					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This is the most important field. Here, you should elaborate a prompt for the AI, defining who it will be, how it should behave, and respond to users."></span>
						<textarea name="prompt" class="form-control text-area-custom-h" id="floatingPrompt" placeholder="Prompt" required><?php if(isset($edit) && $edit){echo ($get->prompt ?? '');} ?></textarea>
						<label for="floatingPrompt">Prompt</label>
					</div>	    
                <?php } ?>						    
		</fieldset>

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><span data-feather="box"></span> Parameters</h5></legend>
			<div class="row">
				<div class="col-md-4">
					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This parameter influences the predictability of the model's responses. Higher values (closer to 1) make the model generate more varied and sometimes unexpected responses, while lower values (closer to 0) make the model more focused and consistent, generating more secure and predictable answers."></span>
						<select name="temperature" class="form-control form-select" id="floatingTemperature" required>
							<?php
							$temperatures = [1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1];
							$selectedTemperature = isset($edit) && $edit ? ($get->temperature ?? 1) : 1;

							foreach ($temperatures as $temp) {
								$selected = $temp == $selectedTemperature ? 'selected' : '';
								echo "<option value='$temp' $selected>$temp</option>";
							}
							?>
						</select>
						<label for="floatingTemperature">Temperature</label>
					</div>
				</div>


				<div class="col-md-4">
					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This parameter helps control word or phrase repetition in the generated response. When you increase this value, the model will try to avoid repetitions."></span>
						<select name="frequency_penalty" class="form-control form-select" id="floatingFrequencyPenalty" required>
							<?php
							$penalties = [1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1, 0];
							$selectedPenalty = isset($edit) && $edit ? ($get->frequency_penalty ?? 0) : 0;

							foreach ($penalties as $penalty) {
								$selected = $penalty == $selectedPenalty ? 'selected' : '';
								echo "<option value='$penalty' $selected>$penalty</option>";
							}
							?>
						</select>
						<label for="floatingFrequencyPenalty">Frequency Penalty</label>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This parameter influences the originality of the model's responses. When you increase this value, the model will attempt to use words or phrases that are less common in the training data."></span>
						<select name="presence_penalty" class="form-control form-select" id="floatingPresencePenalty" required>
							<?php
							$penalties = [1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1, 0];
							$selectedPenalty = isset($edit) && $edit ? ($get->presence_penalty ?? 0) : 0;

							foreach ($penalties as $penalty) {
								$selected = $penalty == $selectedPenalty ? 'selected' : '';
								echo "<option value='$penalty' $selected>$penalty</option>";
							}
							?>
						</select>					        
						<label for="floatingPresencePenalty">Presence Penalty</label>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This parameter sets the minimum number of characters allowed in the chat."></span>
						<input name="chat_minlength" type="number" class="form-control" id="floatingChatMinlength" placeholder="Chat Minlength" value="<?php echo (isset($edit) && $edit) ? ($get->chat_minlength ?? '') : '5'; ?>" required>
						<label for="floatingChatMinlength">Chat Minlength</label>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-floating mb-3">
						<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This parameter sets the maximum number of characters allowed in the chat."></span>
						<input name="chat_maxlength" type="number" class="form-control" id="floatingChatMaxlength" placeholder="Chat Maxlength" value="<?php echo (isset($edit) && $edit) ? ($get->chat_maxlength ?? '') : '1000'; ?>" required>
						<label for="floatingChatMaxlength">Chat Maxlength</label>
					</div>
				</div>

			</div>

		</fieldset>			

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><i class="bi bi-cpu fs-5"></i> Model</h5></legend>
			<div class="row align-middle">
				<div class="col-md-4 align-middle d-flex">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" name="display_API_MODEL" id="floatingDisplayModel" value="1" <?php if (isset($edit) && $edit && ($get->display_API_MODEL ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayModel', 'hiddenDisplayModel')">
						<input type="hidden" name="display_API_MODEL" id="hiddenDisplayModel" value="<?php echo isset($edit) && $edit ? $get->display_API_MODEL : 0; ?>">
						<label class="form-check-label" for="floatingDisplayModel">Display model used in chat? </label>
					</div>
				</div>
				<div class="col-md-3x">
					<div class="form-floating mb-3">
						<select name="API_MODEL" class="form-control form-select" id="floatingAPIModel" required>
							<optgroup label="GPT-3">
								<option value="gpt-3.5-turbo" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo') { echo 'selected'; } ?>>gpt-3.5-turbo</option>
								<option value="stack-ai" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'stack-ai') { echo 'selected'; } ?>>Stack-AI</option>
                                <option value="FlowiseKB" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'FlowiseKB') { echo 'selected'; } ?>>FlowiseKB</option>
								<option value="ft:gpt-3.5-turbo-0613:personal::7rtk0Pr1" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'ft:gpt-3.5-turbo-0613:personal::7rtk0Pr1') { echo 'selected'; } ?>>Discovery Meeting</option>
								<option value="ft:gpt-3.5-turbo-0613:personal::7sUKLvZw" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'ft:gpt-3.5-turbo-0613:personal::7sUKLvZw') { echo 'selected'; } ?>>Recruiting</option>
								<option value="gpt-3.5-turbo-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo-0613') { echo 'selected'; } ?>>gpt-3.5-turbo-0613</option>
								<option value="gpt-3.5-turbo-16k" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo-16k') { echo 'selected'; } ?>>gpt-3.5-turbo-16k</option>
								<option value="gpt-3.5-turbo-16k-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-3.5-turbo-16k-0613') { echo 'selected'; } ?>>gpt-3.5-turbo-16k-0613</option>
							</optgroup>
							<optgroup label="GPT-4">
								<option value="gpt-4" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4') { echo 'selected'; } ?>>gpt-4</option>
								<option value="gpt-4-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4-0613') { echo 'selected'; } ?>>gpt-4-0613</option>
								<option value="gpt-4-32k" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4-32k') { echo 'selected'; } ?>>gpt-4-32k</option>
								<option value="gpt-4-32k-0613" <?php if (isset($edit) && $edit && ($get->API_MODEL ?? '') == 'gpt-4-32k-0613') { echo 'selected'; } ?>>gpt-4-32k-0613</option>
							</optgroup>
						</select>
						<label for="floatingAPIModel">API Model</label>
					</div>
				</div>			  				    				  				  
				<div class="col-md-3">
					<span data-bs-toggle="modal" data-bs-target="#modalModels" class="btn btn-outline-primary mt-2"><i class="bi bi-info-circle"></i> Model's info</span>
				</div>
				
			</div>
		</fieldset>

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5>Display voice icon in chat?</h5></legend>
			<p>By enabling this icon, the play button icon will be displayed in the chat. <br>Subsequently, you must choose between using a system voice (free) or using premium Google voices (paid).</p>
			<div class="row align-middle">
				<div class="col-md-12">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" name="use_google_voice" id="floatingUseGoogleVoice" value="1" <?php if (isset($edit) && $edit && ($get->use_google_voice ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingUseGoogleVoice', 'hiddenUseGoogleVoice')">
						<input type="hidden" name="use_google_voice" id="hiddenUseGoogleVoice" value="<?php echo isset($edit) && $edit ? $get->use_google_voice : 0; ?>">
						<label class="form-check-label" for="floatingUseGoogleVoice">Enable/Disable</label>
					</div>
				</div>
			</div>
		</fieldset>			

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5>Text to Speech System voices <span class="badge bg-success">Free</span></h5></legend>
			<div class="row align-middle">

				<div class="col-md-6">
					<div class="form-floating mb-3">
						<input name="google_voice" type="text" class="form-control" id="floatingGoogleVoice" placeholder="System Voice Name" value="<?php if(isset($edit) && $edit){echo ($get->google_voice ?? '');} ?>">
						<label for="floatingGoogleVoice">System Voice Name</label>
					</div>
				</div>					    				  				  
				
				<div class="col-md-6">
					<div class="form-floating mb-3">
						<input name="google_voice_lang_code" type="text" class="form-control" id="floatingGoogleVoiceLangCode" placeholder="System Voice Lang Code" value="<?php if(isset($edit) && $edit){echo ($get->google_voice_lang_code ?? '');} ?>">
						<label for="floatingGoogleVoiceLangCode">System Voice Lang Code</label>
					</div>		
				</div>					  		
			</div>

			<div class="col">
				<span data-bs-toggle="modal" data-bs-target="#modalSystemVoices" class="btn btn-outline-primary mt-2"><i class="bi bi-info-circle"></i> Check available system voices</span>
			</div>

		</fieldset>

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5>Text to Speech Google Premium voices <span class="badge bg-primary">Paid</span></h5></legend>
			<p>In order to utilize these voices, you need to have a Google Cloud Console Text-to-Speech API key configured in the settings menu -> API keys. <br> By enabling this option, System voices will be ignored</p>
			<div class="row align-middle">

			
				<div class="col-md-12">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" name="use_cloud_google_voice" id="floatingUseCloudGoogleVoice" value="1" <?php if (isset($edit) && $edit && ($get->use_cloud_google_voice ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingUseCloudGoogleVoice', 'hiddenUseCloudGoogleVoice')">
						<input type="hidden" name="use_cloud_google_voice" id="hiddenUseCloudGoogleVoice" value="<?php echo isset($edit) && $edit ? $get->use_cloud_google_voice : 0; ?>">
						<label class="form-check-label" for="floatingUseCloudGoogleVoice">Use Cloud Google Voice</label>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" name="display_mp3_google_cloud_text" id="floatingUseDisplayMp3GoogleCloud" value="1" <?php if (isset($edit) && $edit && ($get->display_mp3_google_cloud_text ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingUseDisplayMp3GoogleCloud', 'hiddenDisplayMp3GoogleCloud')">
						<input type="hidden" name="display_mp3_google_cloud_text" id="hiddenDisplayMp3GoogleCloud" value="<?php echo isset($edit) && $edit ? $get->display_mp3_google_cloud_text : 0; ?>">
						<label class="form-check-label" for="floatingUseDisplayMp3GoogleCloud">After playing audio, show the MP3 download button</label>
					</div>
				</div>				

				<div class="col-md-4">
					<div class="form-floating mb-3">
						<input name="cloud_google_voice" type="text" class="form-control" id="floatingCloudGoogleVoice" placeholder="Google Voice" value="<?php if(isset($edit) && $edit){echo ($get->cloud_google_voice ?? '');} ?>">
						<label for="floatingCloudGoogleVoice">Google Voice Name</label>
					</div>
				</div>					    				  				  
				
				<div class="col-md-4">
					<div class="form-floating mb-3">
						<input name="cloud_google_voice_lang_code" type="text" class="form-control" id="floatingCloudGoogleVoiceLangCode" placeholder="Google Voice Lang Code" value="<?php if(isset($edit) && $edit){echo ($get->cloud_google_voice_lang_code ?? '');} ?>">
						<label for="floatingCloudGoogleVoiceLangCode">Google Voice Lang Code</label>
					</div>		
				</div>					  		

				
				<div class="col-md-4">
					<div class="form-floating mb-3">
						<input name="cloud_google_voice_gender" type="text" class="form-control" id="floatingCloudGoogleVoiceGender" placeholder="Google Voice Gender" value="<?php if(isset($edit) && $edit){echo ($get->cloud_google_voice_gender ?? '');} ?>">
						<label for="floatingCloudGoogleVoiceGender">Google Voice Gender</label>
					</div>		
				</div>

			</div>

			<div class="col">
				<span data-bs-toggle="modal" data-bs-target="#modalGoogleVoices" class="btn btn-outline-primary mt-2"><i class="bi bi-info-circle"></i> Check available Google Cloud Voices</span>
			</div>

		</fieldset>	


		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><span data-feather="mic"></span> Show microphone in chat</h5></legend>
			<div class="row align-middle">


				<div class="col-md-12">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" id="floatingDisplayMicrophone" <?php if (isset($edit) && $edit && ($get->display_mic ?? 0) == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayMicrophone', 'hiddenDisplayMicrophone')">
						<input type="hidden" name="display_mic" id="hiddenDisplayMicrophone" value="<?php echo isset($edit) && $edit ? $get->display_mic : 0; ?>">
						<label class="form-check-label" for="floatingDisplayMicrophone">Use Microphone on input?</label>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-floating mb-3">
						<input name="mic_speak_lang" type="text" class="form-control" id="floatingMicSpeakLang" placeholder="Microphone Speak Lang (Code)" value="<?php if(isset($edit) && $edit){echo ($get->mic_speak_lang ?? '');} ?>">
						<label for="floatingMicSpeakLang">Microphone Speak Lang (Code)</label>
					</div>
				</div>					    				  				  
				
			</div>
		</fieldset>	

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5>Chat options</h5></legend>

			<div class="row">

				<div class="col-md-3">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" id="floatingDisplayAvatar" <?php if ((isset($edit) && $edit && ($get->display_avatar ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayAvatar', 'hiddenDisplayAvatar')">
						<input type="hidden" name="display_avatar" id="hiddenDisplayAvatar" value="<?php echo isset($edit) && $edit ? $get->display_avatar : 1; ?>">
						<label class="form-check-label" for="floatingDisplayAvatar">Show avatar in chat?</label>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-check form-switch custom-switch mb-3">
						<input class="form-check-input" type="checkbox" id="floatingDisplayCopyBtn" <?php if ((isset($edit) && $edit && ($get->display_copy_btn ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayCopyBtn', 'hiddenDisplayCopyBtn')">
						<input type="hidden" name="display_copy_btn" id="hiddenDisplayCopyBtn" value="<?php echo isset($edit) && $edit ? $get->display_copy_btn : 1; ?>">
						<label class="form-check-label" for="floatingDisplayCopyBtn">Show copy chat button?</label>
					</div>
				</div>  

				<div class="col-md-3">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" id="floatingFilterBadwords" <?php if ((isset($edit) && $edit && ($get->filter_badwords ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingFilterBadwords', 'hiddenFilterBadwords')">
						<input type="hidden" name="filter_badwords" id="hiddenFilterBadwords" value="<?php echo isset($edit) && $edit ? $get->filter_badwords : 1; ?>">
						<label class="form-check-label" for="floatingFilterBadwords">Filter bad words?</label>
					</div>
				</div>


				<div class="col-md-3">
					<div class="form-check form-switch custom-switch mb-3">
						<input class="form-check-input" type="checkbox" id="floatingChatContactList" <?php if ((isset($edit) && $edit && ($get->display_contacts_user_list ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingChatContactList', 'hiddenChatContactList')">
						<input type="hidden" name="display_contacts_user_list" id="hiddenChatContactList" value="<?php echo isset($edit) && $edit ? $get->display_contacts_user_list : 1; ?>">
						<label class="form-check-label" for="floatingChatContactList">Display Contact List</label>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-check form-switch custom-switch mb-3">
						<input class="form-check-input" type="checkbox" id="floatingDisplayShareButton" <?php if ((isset($edit) && $edit && ($get->display_share ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayShareButton', 'hiddenShareButton')">
						<input type="hidden" name="display_share" id="hiddenShareButton" value="<?php echo isset($edit) && $edit ? $get->display_share : 1; ?>">
						<label class="form-check-label" for="floatingDisplayShareButton">Display Share Button</label>
					</div>
				</div>				


		        <div class="col-md-3">
		          <div class="form-floating mb-3">
		          	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This value represents the number of message history that the system will send to the OpenAI API for it to remember previous conversations. It is important to note that the larger the history, the more tokens will be consumed, and it may potentially reach the API's token limit. We recommend wisely balancing this value."></span>
		            <select name="array_message_history" class="form-control form-select" id="floatingArrayMessageHistory" required>
		              <?php
		              $array_message_historys = [6, 7, 8, 9, 10, 11, 12];
		              $selected_array_message_history = isset($edit) && $edit ? ($get->array_message_history ?? 1) : 8;

		              foreach ($array_message_historys as $temp) {
		                $selected = $temp == $selected_array_message_history ? 'selected' : '';
		                echo "<option value='$temp' $selected>$temp</option>";
		              }
		              ?>
		            </select>
		            <label for="floatingArrayMessageHistory">Message array size</label>
		          </div>
		        </div>	

	            <div class="col-md-3">
		            <div class="form-floating mb-3">
		               <span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Sometimes the message you send to the OpenAI API can be too long and needs to be truncated. This value represents the maximum character limit that the message should reach in order to be truncated."></span>
		              <input name="array_message_limit_length" type="number" class="form-control" id="floatingArrayMessageLimit" placeholder="Message character truncation limit" value="<?php echo isset($edit) && $edit ? ($get->array_message_limit_length ?? 1) : 500; ?>">
		              <label for="floatingArrayMessageLimit">Message character truncation limit</label>
		            </div>
	            </div>                  


			</div>
		</fieldset>	

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><i class="bi bi-globe fs-5"></i> Language output option</h5></legend>
			<p>You have the option to set a default output language and hide the selection box, or allow the user to freely choose their preferred language.</p>
			<div class="row align-middle">

				<div class="col-md-12 align-middle d-flex">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" id="floatingDisplayPromptOutput" <?php if ((isset($edit) && $edit && ($get->display_prompts_output ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayPromptOutput', 'hiddenDisplayPromptOutput')">
						<input type="hidden" name="display_prompts_output" id="hiddenDisplayPromptOutput" value="<?php echo isset($edit) && $edit ? $get->display_prompts_output : 1; ?>">
						<label class="form-check-label" for="floatingDisplayPromptOutput">Show language output checkbox</label>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-floating mb-3">
						<select name="id_prompts_output_default" class="form-control form-select">
							<option value="*">Default</option>
							<?php foreach ($getPromptsOutput as $show_prompts_output) {?>
								<option <?php echo ((isset($edit) && $edit) && ($get->id_prompts_output_default ?? '') == $show_prompts_output->id) ? 'selected' : ''; ?> value="<?php echo $show_prompts_output->id; ?>"><?php echo $show_prompts_output->name; ?></option>
							<?php } ?>
						</select>
						<label>Default language output</label>
					</div>
				</div>
			</div>
		</fieldset>

		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><i class="bi bi-chat-heart fs-5"></i> Tone option</h5></legend>
			<p>You have the option to set a default tone and hide the selection box, or allow the user to freely choose their preferred tone.</p>
			<div class="row align-middle">

				<div class="col-md-12 align-middle d-flex">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" id="floatingDisplayPromptTone" <?php if ((isset($edit) && $edit && ($get->display_prompts_tone ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayPromptTone', 'hiddenDisplayTone')">
						<input type="hidden" name="display_prompts_tone" id="hiddenDisplayTone" value="<?php echo isset($edit) && $edit ? $get->display_prompts_tone : 1; ?>">
						<label class="form-check-label" for="floatingDisplayPromptTone">Show tone checkbox</label>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-floating mb-3">
						<select name="id_prompts_tone_default" class="form-control form-select">
							<option value="*">Default</option>
							<?php foreach ($getPromptsTone as $show_tone_output) {?>
								<option <?php echo ((isset($edit) && $edit) && ($get->id_prompts_tone_default ?? '') == $show_tone_output->id) ? 'selected' : ''; ?> value="<?php echo $show_tone_output->id; ?>"><?php echo $show_tone_output->name; ?></option>
							<?php } ?>
						</select>
						<label>Default tone</label>
					</div>
				</div>
			</div>
		</fieldset>


		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><i class="bi bi-chat-left-quote fs-5"></i> Writing Style</h5></legend>
			<p>You have the option to set a default writing style and hide the selection box, or allow the user to freely choose their writing style.</p>
			<div class="row align-middle">

				<div class="col-md-12 align-middle d-flex">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" id="floatingDisplayPromptWriting" <?php if ((isset($edit) && $edit && ($get->display_prompts_writing ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayPromptWriting', 'hiddenDisplayWriting')">
						<input type="hidden" name="display_prompts_writing" id="hiddenDisplayWriting" value="<?php echo isset($edit) && $edit ? $get->display_prompts_writing : 1; ?>">
						<label class="form-check-label" for="floatingDisplayPromptWriting">Show tone checkbox</label>
					</div>
				</div>

				<div class="col-md-12">
					<div class="form-floating mb-3">
						<select name="id_prompts_writing_default" class="form-control form-select">
							<option value="*">Default</option>
							<?php foreach ($getPromptsWriting as $show_writing_output) {?>
								<option <?php echo ((isset($edit) && $edit) && ($get->id_prompts_writing_default ?? '') == $show_writing_output->id) ? 'selected' : ''; ?> value="<?php echo $show_writing_output->id; ?>"><?php echo $show_writing_output->name; ?></option>
							<?php } ?>
						</select>
						<label>Default tone</label>
					</div>
				</div>
			</div>
		</fieldset>	

		<?php if(isset($edit) && $edit){?>
		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><i class="bi bi-code-slash"></i> Allow embed chat (iframe)</h5></legend>
			<div class="row align-middle">

				<div class="col-md-12 align-middle d-flex">
					<div class="form-check form-switch mb-3 custom-switch">
						<input class="form-check-input" type="checkbox" id="floatingDisplayAllowEmbedChat" <?php if ((isset($edit) && $edit && ($get->allow_embed_chat ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayAllowEmbedChat', 'hiddenDisplayAllowEmbedChat')">
						<input type="hidden" name="allow_embed_chat" id="hiddenDisplayAllowEmbedChat" value="<?php echo isset($edit) && $edit ? $get->allow_embed_chat : 1; ?>">
						<label class="form-check-label" for="floatingDisplayAllowEmbedChat">Allow embed chat</label>
					</div>
				</div>
			</div>
		</fieldset>	
		<?php } ?>

		<fieldset class="border rounded-2 p-3 mb-4">
		<legend><h5><i class="bi bi-card-image"></i> Use DALL-E</h5></legend>

		<div class="col-md-12">
		  <div class="form-check form-switch mb-3 custom-switch">
		    <input class="form-check-input" type="checkbox" id="floatingDisplayUseDalle" <?php if (isset($get->use_dalle) && $get->use_dalle == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayUseDalle', 'hiddenDisplayUseDalle')">
		    <input type="hidden" name="use_dalle" id="hiddenDisplayUseDalle" value="<?php echo isset($get->use_dalle) ? $get->use_dalle : 0; ?>">
		    <label class="form-check-label" for="floatingDisplayUseDalle">Enable/Disable DALL-E</label>
		  </div>        
		</div>

    	</fieldset>


		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5><i class="bi bi-lightbulb fs-5"></i> Suggestions</h5></legend>
			<p>In order for the suggestion icon to appear, you need to have at least 1 result registered.</p>
			<div class="col-md-auto align-middle d-flex">
				<div class="form-check form-switch mb-3 custom-switch">
					<input class="form-check-input" type="checkbox" id="floatingDisplaySuggestions" <?php if ((isset($edit) && $edit && ($get->display_suggestions ?? 0) == 1) || (!isset($edit) || !$edit)) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplaySuggestions', 'hiddenDisplaySuggestions')">
					<input type="hidden" name="display_suggestions" id="hiddenDisplaySuggestions" value="<?php echo isset($edit) && $edit ? $get->display_suggestions : 1; ?>">
					<label class="form-check-label" for="floatingDisplaySuggestions">Show prompt suggestions in chat</label>
				</div>
			</div>

			<div class="row align-middle overflow-x-auto" style="max-height:420px">
				<div class="col-md-12">
					<div id="descriptionFields">
						<?php 
						if(isset($edit) && $edit){
							$suggestionsArray = json_decode($get->suggestions);
							if (empty($suggestionsArray)) {
								$suggestionsArray = array("");
							}
							foreach ($suggestionsArray as $suggestions) {
								generateSuggestionsField($suggestions);
							}
						} else {
							generateSuggestionsField();
						}
						?>
					</div>
				</div>	
			</div>
		</fieldset>						

<?php
require_once(__DIR__."/../../inc/default-form-footer.php");
require_once(__DIR__."/../../inc/footer.php");
?>