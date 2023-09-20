<?php
$module_name = "menus";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $menus->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
}
?>

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Menus</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/menus" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/menus/action" method="post" novalidate enctype="multipart/form-data">
				 <fieldset class="border rounded-2 p-3 mb-4">
				    <legend><h5>Fill in the fields below:</h5></legend>

					  <div class="row">
					    <div class="col-md-2">
				        <div class="form-floating mb-3">
				          <input name="name" type="text" class="form-control" <?php echo (isset($edit) && $edit) ? '' : 'id="floatingInputName"'; ?> placeholder="Name" value="<?php echo isset($get->name) ? $get->name : ''; ?>" required>
				          <label for="floatingInputName">Name</label>
				        </div>	   			    	
					    </div>

					    
					    <div class="col-md-3">
				        <div class="form-floating mb-3">
				          <input name="slug" type="text" class="form-control" id="floatingInputSlug" placeholder="Url" value="<?php if(isset($edit) && $edit){echo ($get->slug ?? '');} ?>" required>
				          <label for="floatingInputSlug">Url</label>
				        </div>	   			    	
					    </div>
					  	

					  
							<div class="col-md-3">
							    <div class="form-floating mb-3">
											<select name="position" class="form-control" id="floatingPosition" required>
											    <?php
											    $temperatures = ["Header","Footer Col1","Footer Col2","Not display"];
											    $selectedTemperature = isset($edit) && $edit ? ($get->position ?? 1) : 1;

											    foreach ($temperatures as $temp) {
											        $selected = $temp == $selectedTemperature ? 'selected' : '';
											        echo "<option value='$temp' $selected>$temp</option>";
											    }
											    ?>
											</select>
							        <label for="floatingPosition">Position</label>
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

							<div class="col-md-2 align-middle d-flex">
							    <div class="form-check form-switch mb-3 custom-switch">
							        <?php 
							            $target_blank = isset($edit) && $edit ? ($get->target_blank ?? 1) : 1;
							            $checked = $target_blank == 1 ? 'checked' : '';
							        ?>
							        <input class="form-check-input" type="checkbox" id="floatingtarget_blank" <?php echo $checked; ?> onchange="updateSwitchValue('floatingtarget_blank', 'hiddentarget_blank')">
							        <input type="hidden" name="target_blank" id="hiddentarget_blank" value="<?php echo $target_blank; ?>">
							        <label class="form-check-label" for="floatingtarget_blank">Open in new tab</label>
							    </div>
							</div>																	


				 </fieldset>


	        <div class="d-grid">
	          <button class="btn btn-success text-uppercase fw-bold mb-2 submit-button" type="submit">Save</button>
	        </div>

	       <input type="hidden" name="id" value="<?php echo @$edit ? $get->id : ''; ?>">
	       <input type="hidden" name="action" value="<?php echo @$edit ? 'edit' : 'add'; ?>">
	      </form>
      </div>

 
			<div id="formErrorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
			  <div class="d-flex">
			    <div class="toast-body">
			      <i class="bi bi-exclamation-octagon"></i>Attention: Please check all mandatory fields.
			    </div>
			    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			  </div>
			</div>			

<?php
require_once("../../inc/footer.php");
?>