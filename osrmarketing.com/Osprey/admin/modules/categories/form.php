<?php
$module_name = "categories";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $categories->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
}
?>


      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Categories</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/categories" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/categories/action" method="post" novalidate enctype="multipart/form-data" id="form">
				 <fieldset class="border rounded-2 p-3 mb-4">
				    <legend><h5>Fill in the fields below:</h5></legend>

					  <div class="row">
						  <div class="col-md-3">
								<div class="mb-3">
										
										<div class="wrapper-image-preview-form p-5">
											<input name="image" type="file" class="form-control" id="image" accept="image/*" onchange="loadPreviewImage(event, 'imagePreview')">
											<img class="img-fluid" id="imagePreview" src="<?php echo !empty($get->image) ? $base_url . '/public_uploads/' . $get->image : 'https://placehold.co/150x150'; ?>" onerror="this.src='https://placehold.co/150x150'">
										</div>
										<div class="mb-1">Size: 150x150 (Format: svg or png)</div>
								</div>
						  </div>

						  <div class="col-md-9">

						    <div class="col-md-12">
					        <div class="form-floating mb-3">
					          <input name="name" type="text" class="form-control" <?php echo (isset($edit) && $edit) ? '' : 'id="floatingInputName"'; ?> placeholder="Name" value="<?php echo isset($get->name) ? $get->name : ''; ?>" required>
					          <label for="floatingInputName">Name</label>
					        </div>	   			    	
						    </div>

						    <div class="col-md-12">
					        <div class="form-floating mb-3">
					          <input name="slug" type="text" class="form-control" id="floatingInputSlug" placeholder="Slug" value="<?php if(isset($edit) && $edit){echo ($get->slug ?? '');} ?>" required>
					          <label for="floatingInputSlug">Slug</label>
					        </div>	   			    	
						    </div>

						  </div>

							<div class="col-md-2 align-middle d-flex">
							    <div class="form-check form-switch mb-3 custom-switch">
							        <?php 
							            $status = isset($edit) && $edit ? ($get->status ?? 1) : 1;
							            $checked = $status == 1 ? 'checked' : '';
							        ?>
							        <input class="form-check-input" type="checkbox" id="floatingStatus" <?php echo $checked; ?> onchange="updateSwitchValue('floatingStatus', 'hiddenStatus')">
							        <input type="hidden" name="status" id="hiddenStatus" value="<?php echo $status; ?>">
							        <label class="form-check-label" for="floatingStatus">Status</label>
							    </div>
							</div>							


					   </div>	    

				 </fieldset>
							         

<?php
require_once(__DIR__."/../../inc/default-form-footer.php");
require_once(__DIR__."/../../inc/footer.php");
?>