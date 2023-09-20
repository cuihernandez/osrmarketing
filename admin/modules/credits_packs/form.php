<?php
$module_name = "credits_packs";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
$getPrompts = $prompts->getListFront();
$getMaxTier = $credits_packs->getMaxTier();

function generateDescriptionField($description = null) {
    $valueAttribute = $description ? ' value="' . htmlspecialchars($description) . '"' : '';
    echo '
        <div class="input-group mb-3 descriptionField">
            <input type="text" name="description[]" class="form-control" placeholder="Description"' . $valueAttribute . '>
            <button type="button" class="btn btn-success addDescription"><i class="bi bi-plus-circle"></i> Add new</button>
            <button type="button" class="btn btn-danger removeDescription"><i class="bi bi-trash"></i> Remove</button>
        </div>
    ';
}


if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $credits_packs->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/".$module_name);
		die();
	}
	$creditsPacksArray = array();
	$getCreditsPackByIdCreditPack = $prompts_credits_packs->getListByIdCreditPack($get->id);
	
	foreach ($getCreditsPackByIdCreditPack as $showCreditsPacks) {
	    $creditsPacksArray[] = $showCreditsPacks->id_prompt; 
	}    
}
?>


      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Credits Pack</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/credits_packs" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div>
	      <form action="/admin/credits_packs/action" method="post" novalidate enctype="multipart/form-data" id="form">
			 <fieldset class="border rounded-2 p-3 mb-4">
			    <legend><h5>Fill in the fields below:</h5></legend>

				  <div class="row">

				    <div class="col-md-3 col-xl-2 col-lg-3">
					    <div class="mb-3">
						    <div class="wrapper-image-preview-form wrapper-image-preview-form-icon">
								<input name="image" type="file" class="form-control" id="image" accept="image/*" onchange="loadPreviewImage(event, 'imagePreview')">
								<img class="img-fluid" id="imagePreview" src="<?php echo !empty($get->image) ? $base_url . '/public_uploads/' . $get->image : '#'; ?>" onerror="this.src='https://placehold.co/256x256'">
						    </div>
					    </div>

							<div class="col-md-12 align-middle d-flex">
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

				    <div class="col-md-10">
				    	<div class="row">
				    	
						    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Please enter the name of the package that will be displayed on the pricing page."></span>
						          <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="Name (label)" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
						          <label for="floatingInputName">Name (label)</label>
						        </div>	   			    	
						    </div>

						    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Enter the three letters that represent your currency, for example: USD, BRL, AED."></span>
						          <input name="currency_code" type="text" class="form-control" id="floatingInputCurrencyCode" placeholder="Currency Code" value="<?php if(isset($edit) && $edit){echo ($get->currency_code ?? '');} ?>" required>
						          <label for="floatingInputCurrencyCode">Currency Code</label>
						        </div>	   			    	
						    </div>

						    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="In the amount field, the value must always be expressed in cents. Therefore, if you want to charge 10 dollars, you should enter 1000 in the field."></span>
						          <input name="amount" type="text" class="form-control" id="floatingInputAmount" placeholder="Amount" value="<?php if(isset($edit) && $edit){echo ($get->amount  ?? '');} ?>" required>
						          <label for="floatingInputAmount">Amount</label>
						        </div>	   			    	
						    </div>

						    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="You can write in this field however you prefer, for example: 10$. It's just a label to display in prices."></span>
						          <input name="price" type="text" class="form-control" id="floatingInputPrice" placeholder="Price" value="<?php if(isset($edit) && $edit){echo ($get->price ?? '');} ?>" required>
						          <label for="floatingInputPrice">Price (Label)</label>
						        </div>	   			    	
						    </div>

						    <div class="col-md-4">
						        <div class="form-floating mb-3">
						        	<span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="This field represents the amount of credits that will be assigned to the user's account after the package purchase is completed."></span>
						          <input name="credit" type="text" class="form-control" id="floatingInputCredits" placeholder="Credits" value="<?php if(isset($edit) && $edit){echo ($get->credit ?? '');} ?>" required>
						          <label for="floatingInputCredits">Credits</label>
						        </div>	   			    	
						    </div>

						    <div class="col-md-4">
						        <div class="form-floating mb-3">
						          <span tabindex="-1" class="bi bi-question-circle tool-tip-form-float" data-bs-toggle="tooltip" title="Tear represents the different hierarchical levels of the pricing package. For example, the most affordable package can be referred to as tier 1, the second more expensive level would be tier 2, the next one tier 3, then tier 4, and so on. The tear is relevant if you want to allow users to access bots from previous packages, in case they have a package from a higher level. This option can be controlled through the settings menu » VIP"></span>
						          <input name="tier" type="text" class="form-control" id="floatingInputTier" placeholder="Tier" value="<?php echo isset($edit) && $edit ? $get->tier : ($getMaxTier->max_tier+1); ?>" required>


						          <label for="floatingInputTier">Tier</label>
						        </div>	   			    	
						    </div>

								<div class="col-md-6">
								    <div id="descriptionFields">
								        <?php 
										if(isset($edit) && $edit){
										    $descriptionArray = json_decode($get->description);
										    if (empty($descriptionArray)) {
										        $descriptionArray = array("");
										    }
										    foreach ($descriptionArray as $description) {
										        generateDescriptionField($description);
										    }
										} else {
										    generateDescriptionField();
										}
								        ?>
								    </div>
								</div>		

								<div class="col-md-12">
									<div class="alert alert-light">Please refer to the documentation for more information:<br>
									<a target="_blank" href="https://stripe.com/docs/currencies">https://stripe.com/docs/currencies</a>
									<br>
									<a target="_blank" href="https://developer.paypal.com/api/rest/reference/currency-codes/">https://developer.paypal.com/api/rest/reference/currency-codes/</a>
									</div>
								</div>

								<div class="col-md-12">

								<fieldset class="border rounded-2 p-3 mb-4">
								    <legend><h5>Manage vip access</h5></legend>
							    	<div class="col-12">
							    		<div class="alert alert-light">
							    			<h4>Instructions:</h4>
							    			<p>The selection of the bots below requires the user to purchase either this package.<br>To make a bot "Free," do not select it in this or any package plan.<br>
							    				<a href="<?php echo $base_url;?>/admin/settings#nav-vip-tab">View extra vip behavior options</a>
							    			</p>
						    			</div>
								    </div>
								    <div class="row">
										<?php foreach ($getPrompts as $showPrompts) {
											$checkPackage = $prompts_credits_packs->getListByIdPrompt($showPrompts->id)->FetchAll();
										?>
										    <div class="col-sm-6 col-md-6 col-lg-6 mb-2">
										    	<div class="custom-switch-package">
											        <div class="form-check form-switch custom-switch me-4">
											            <input class="form-check-input" type="checkbox" id="floatingPrompt<?php echo $showPrompts->id; ?>" name="prompts_credits_packs[]" value="<?php echo $showPrompts->id; ?>"
											            <?php if(isset($creditsPacksArray) && in_array($showPrompts->id, $creditsPacksArray)){ echo 'checked'; } ?>>
											            <label class="form-check-label label-prompt-wrapper" for="floatingPrompt<?php echo $showPrompts->id; ?>">
											            	<div class="label-prompt">
											            		<div class="label-prompt-image"><img src="<?php echo $base_url."/public_uploads/".$showPrompts->image; ?>" onerror="this.src='https://placehold.co/640x700'"></div>
											            		<div class="label-prompt-info">
											            			<h4><?php echo $showPrompts->name; ?></h4>
											            			<h5 class="mb-0"><?php echo $showPrompts->expert; ?></h5>
											            			<?php 
																	if(!$checkPackage){
																		echo "<span class='badge rounded-pill bg-success me-1'>Free</span>";
																	}else{
																		foreach ($checkPackage as $showPackage) {

																			$checkCreditPack = $credits_packs->get($showPackage->id_credits_pack);
																			echo "<span class='badge rounded-pill bg-" . (isset($edit) && $get->id == $checkCreditPack->id ? "primary" : "secondary") . " me-1 mb-1'>" . $checkCreditPack->name . "</span>";
																		}
																	}
																	?>
											            		</div>
											            	</div>
											            </label>
											        </div>
										        </div>
										    </div>
										<?php } ?>
								    </div>

								</fieldset>	

								</div>

				    	</div>
				    </div>

				   </div>	    

			 </fieldset>
							         

<?php
require_once(__DIR__."/../../inc/default-form-footer.php");
require_once(__DIR__."/../../inc/footer.php");
?>