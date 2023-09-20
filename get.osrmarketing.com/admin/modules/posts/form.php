<?php
$module_name = "posts";
$use_save_absolute = true;
$use_codemirror = true;
$use_bootstrap_icons = true;
$use_select2 = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
$getTags = $tags->getList();
$tagsArray = array();
$getPrompts = $prompts->getList();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $posts->get($_REQUEST['id']);
	$getTagsByIdPost = $posts_tags->getListByIdPost($get->id);

	foreach ($getTagsByIdPost as $showTags) {
	    $tagsArray[] = $showTags->id_tag;
	}
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
}

$getPromptsOutput = $prompts_output->getListFront();
$getPromptsOutputCount = $getPromptsOutput->rowCount();
$getPromptsTone = $prompts_tone->getListFront();
$getPromptsToneCount = $getPromptsTone->rowCount();
$getPromptsWriting = $prompts_writing->getListFront();
$getPromptsWritingCount = $getPromptsWriting->rowCount();
?>

	<div class="modal fade" id="modalOutputBlogText" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog modal-fullscreen">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="modalOutputBlogTextLabel">Draft area - Create a text using AI</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
	      </div>
	      <div class="modal-body">


			<div class="container-fluid">
			    <div class="row">
			      <div class="col-md-4">

			        <div class="row">
			          <div class="col-12">
			            <div class="mb-3">
			              <label for="textPrompt" class="mb-1">Select an expert to write the text (Prefer the copywriters)</label>
			              <select id="textPrompt" name="id_prompt" class="form-control form-select">
			              	<option value="">Select</option>
			              	<?php foreach ($getPrompts as $showPrompts) {?>
			                	<option value="<?php echo $showPrompts->id; ?>"><?php echo $showPrompts->name; ?> (<?php echo $showPrompts->expert; ?>)</option>
			              	<?php } ?>
			              </select>
			            </div>
			          </div>

			       	  <div class="col-12">
			            <div class="mb-3">
			              <label for="summary" class="mb-1">Summary</label>
			              <textarea class="form-control" id="summary" name="summary" placeholder="Example: The day-to-day life of a cat."></textarea>
			            </div>
			          </div>

			          <div class="col-12">
			            <div class="mb-3">
			              <label for="keywords" class="mb-1">Keywords</label>
			              <input type="text" id="keywords" name="keywords" class="form-control" placeholder="Example: Cats, Pets, Feline behavior, Cat care">
			            </div>
			          </div>

			          <div class="col-12">
			            <div class="mb-3">
			              <label for="audience" class="mb-1">Target Audience</label>
			              <input type="text" id="audience" name="audience" class="form-control" placeholder="Example: Cat owners, Animal enthusiasts, Cat lovers, etc...">
			            </div>
			          </div>


			          <div class="col-6">
			            <div class="mb-3">
			              <label for="minParagraphs" class="mb-1">Minimum Paragraphs</label>
			              <input type="number" id="minParagraphs" name="minParagraphs" class="form-control" value="1">
			            </div>
			          </div>

			          <div class="col-6">
			            <div class="mb-3">
			              <label for="maxParagraphs" class="mb-1">Maximum Paragraphs</label>
			              <input type="number" id="maxParagraphs" name="maxParagraphs" class="form-control" value="20">
			            </div>
			          </div>


						<div class="col-md-6">
							<div class="mb-3">
								<label class="bm-1">Text Tone</label>
								<select id="id_prompts_tone_default" name="id_prompts_tone_default" class="form-control form-select">
									<option value="">Default</option>
									<?php foreach ($getPromptsTone as $show_tone_output) {?>
										<option <?php echo ((isset($edit) && $edit) && ($get->id_prompts_tone_default ?? '') == $show_tone_output->id) ? 'selected' : ''; ?> value="<?php echo $show_tone_output->id; ?>"><?php echo $show_tone_output->name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="col-md-6">
							<div class="mb-3">
								<label class="bm-1">Writing Style</label>
								<select id="id_prompts_writing_default" name="id_prompts_writing_default" class="form-control form-select">
									<option value="*">Default</option>
									<?php foreach ($getPromptsWriting as $show_writing_output) {?>
										<option <?php echo ((isset($edit) && $edit) && ($get->id_prompts_writing_default ?? '') == $show_writing_output->id) ? 'selected' : ''; ?> value="<?php echo $show_writing_output->id; ?>"><?php echo $show_writing_output->name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>						

						<div class="col-md-12">
							<div class="mb-3">
								<label class="mb-1">Language</label>
								<select id="id_prompts_output_default" name="id_prompts_output_default" class="form-control form-select">
									<option value="">Default</option>
									<?php foreach ($getPromptsOutput as $show_prompts_output) {?>
										<option <?php echo ((isset($edit) && $edit) && ($get->id_prompts_output_default ?? '') == $show_prompts_output->id) ? 'selected' : ''; ?> value="<?php echo $show_prompts_output->id; ?>"><?php echo $show_prompts_output->name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

			           <div class="col-12 text-end">
			           	<button class="btn btn-success btn-create-post-ai"><i class="bi bi-cpu fs-6"></i> Create text</button>
			           </div>

			    	</div>
			      	
			      </div>
			      <div class="col-md-8 ms-auto">
			      	<div id="modal-output-post-body" contenteditable="true"></div>
			      	<div class="text-end">
					<button class="btn btn-primary" onclick="copyAITextPost();"><i class="bi bi-file-text"></i> Include text in my post</button>
			        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Close</button>
			        </div>
			      </div>
			    </div>
			</div>

	        
	      </div>
	    </div>
	  </div>
	</div>



	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<h1 class="h2">Post</h1>
		<div class="btn-toolbar mb-2 mb-md-0">
			<?php if(isset($edit) && $edit){?>
			<a href="<?php echo $base_url."/blog/".$get->slug; ?>" target="_blank" class="btn btn-primary me-2"><i class="bi bi-box-arrow-up-right"></i> View Post</a>
			<?php } ?>
			<a href="<?php echo $base_url; ?>/admin/posts" class="btn btn-danger btn-primary">Cancel</a>
		</div>
	</div>


	<form action="/admin/posts/action" method="post" novalidate enctype="multipart/form-data" id="form">


		<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5>Basic information</h5></legend>

			<div class="row">
				<div class="col-md-4 col-lg-3 col-xl-2">

					<div class="mb-3">
						<div class="wrapper-image-preview-form">
							<input name="image" type="file" class="form-control" id="image" accept="image/*" onchange="loadPreviewImage(event, 'imagePreview')">
							<img class="img-fluid" id="imagePreview" src="<?php echo !empty($get->image) ? $base_url . '/public_uploads/' . $get->image : '#'; ?>" onerror="this.src='https://placehold.co/1200x628'">
						</div>
					</div>	

				</div>
				<div class="col-md-8 col-lg-9 col-xl-10">

					<div class="row">

						<div class="col-md-12">
							<div class="form-check form-switch mb-3 custom-switch">
								<input class="form-check-input" type="checkbox" id="floatingDisplayStatus" 
								<?php if (!isset($edit) || ($edit && ($get->status ?? 0) == 1)) { echo 'checked'; } ?>
								onchange="updateSwitchValue('floatingDisplayStatus', 'hiddenDisplayStatus')">
								<input type="hidden" name="status" id="hiddenDisplayStatus" 
								value="<?php echo (!isset($edit) || ($edit && $get->status == 1)) ? 1 : 0; ?>">
								<label class="form-check-label" for="floatingDisplayStatus">Published / Draft</label>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-floating mb-3">
								<input name="title" type="text" class="form-control" <?php echo (isset($edit) && $edit) ? '' : 'id="floatingInputName"'; ?> placeholder="Title" value="<?php echo isset($get->title) ? $get->title : ''; ?>" required>
								<label for="floatingInputName">Title</label>
							</div>	   			    	
						</div>					    

						<div class="col-md-4">
							<div class="form-floating mb-3">
								<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="The slug is the field that is inserted in the URL to identify a specific page, such as /chat/ai-name, where 'ai-name' is the slug that identifies the corresponding AI page."></span>
								<input name="slug" type="text" class="form-control" id="floatingInputSlug" placeholder="Expert" value="<?php if(isset($edit) && $edit){echo ($get->slug ?? '');} ?>" required>
								<label for="floatingInputSlug">Slug (URL)</label>
							</div>		             			    	
						</div>

						<div class="col-md-4">
						    <div class="form-floating mb-3">
						        <input name="publication_date" type="datetime-local" class="form-control" placeholder="Title" value="<?php echo isset($get->publication_date) ? date('Y-m-d\TH:i', strtotime($get->publication_date)) : ''; ?>" required>
						        <label>Publication Date</label>
						    </div>	   			    	
						</div>					

						<div class="col-md-12">
							<div class="form-floating mb-3">
								<textarea name="resume" class="form-control text-area-custom-h" placeholder="Resume" required><?php if(isset($edit) && $edit){echo ($get->resume ?? '');} ?></textarea>
								<label>Resume</label>
							</div>							
						</div>


					</div>

				</div>		    

			</fieldset>

		    <fieldset class="border rounded-2 p-3 mb-4">
		      <legend><h5>Post Content</h5></legend>
		      
		      <div class="mb-3">
				<a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalOutputBlogText"> Create a text using AI</a>
		      	<button id="openKCFinder" class="btn btn-primary"><i class="bi bi-images"></i> Picture library</button>
			  </div>

		      <div class="row">
		      	<div class="col-12">
			      <div class="mb-3">
			        <textarea class="editor" name="content" data-height="500"><?php echo isset($get->content) ? $get->content : ''; ?></textarea>
			      </div>        		      		
		      	</div>
		      </div>

		    </fieldset>  			

			<fieldset class="border rounded-2 p-3 mb-4">
			<legend><h5>SEO</h5></legend>
				<div class="col-md-12">
					<div class="form-floating mb-3">
						<input name="meta_title" type="text" class="form-control"  placeholder="Meta Title (SEO)" value="<?php if(isset($edit) && $edit){echo ($get->meta_title ?? '');} ?>" required>
						<label>Meta Title (SEO)</label>
					</div>	   			    	
				</div>		

				<div class="col-md-12">
					<div class="form-floating mb-3">
						<textarea name="meta_description" class="form-control" placeholder="Meta Description (SEO)" required><?php if(isset($edit) && $edit){echo ($get->meta_description ?? '');} ?></textarea>
						<label>Meta Description (SEO)</label>
					</div>
				</div>
			</fieldset>			

			<fieldset class="border rounded-2 p-3 mb-4">
				<legend><h5>Post Tags</h5></legend>

				<div class="form-floating mb-3">
					<select class="form-select select2" id="multiple-select-field" name="tags[]" data-placeholder="Tags" multiple>
						<?php foreach ($getTags as $showTags) {?>
							<option 
							value="<?php echo $showTags->id; ?>" 
							<?php echo in_array($showTags->id, $tagsArray) ? 'selected' : ''; ?>
							>
							<?php echo $showTags->name; ?><?php echo $showTags->status == '0' ? ' (Disabled)' : ''; ?>
						</option>
					<?php } ?>
				</select>
			</div>	
			
		</fieldset>




<?php
require_once(__DIR__."/../../inc/default-form-footer.php");
require_once(__DIR__."/../../inc/footer.php");
?>