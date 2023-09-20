<?php
$module_name = "prompts_writing";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $prompts_writing->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
}
?>

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Writing style</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/prompts_writing" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/prompts_writing/action" method="post" novalidate enctype="multipart/form-data" id="form">
				 <fieldset class="border rounded-2 p-3 mb-4">
				    <legend><h5>Fill in the fields below:</h5></legend>

					  <div class="row">
					    <div class="col-md-4">
				        <div class="form-floating mb-3">
				          <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="Name (label)" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
				          <label for="floatingInputName">Name (label)</label>
				        </div>	   			    	
					    </div>

					    <div class="col-md-4">
				        <div class="form-floating mb-3">
				          <input name="value" type="text" class="form-control" id="floatingInputValue" placeholder="Value" value="<?php if(isset($edit) && $edit){echo ($get->value ?? '');} ?>" required>
				          <label for="floatingInputValue">Value</label>
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