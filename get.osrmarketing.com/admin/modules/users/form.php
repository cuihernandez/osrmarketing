<?php
$module_name = "users";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == "edit") {
	$edit = true;
	$get = $users->get($_REQUEST['id']);
	if(!$get){
		header("location:".$base_url."/admin/users");
		die();
	}
}
function renderPermissionSwitch($permission, $value, $icon = '', $label = '') {
    $checked = in_array($value, $permission) ? 'checked' : '';
    echo "
    <div class=\"col-lg-3 col-sm2 align-middle d-flex mb-3\">
        <div class=\"form-check form-switch custom-switch\">
            <input class=\"form-check-input\" type=\"checkbox\" id=\"floating$value\" name=\"permission[]\" value=\"$value\" $checked>
            <label class=\"form-check-label\" for=\"floating$value\">$icon $label</label>
        </div>
    </div>
    ";
}

$permission = isset($edit) && $edit && is_array(json_decode($get->permission)) ? json_decode($get->permission) : [];
?>

      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Admin users</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
					<a href="<?php echo $base_url; ?>/admin/users" class="btn btn-danger btn-primary">Cancel</a>
        </div>
      </div>

      <div class="col">
		  <div class="alert alert-light">
	        <h6 class="mb-0"><i class="bi bi-info-circle-fill"></i> Remember: leave the password field empty if you don't want to change it. If you decide to change the password, write it down in a secure place, as it will not be possible to access the system without it.</h6>
	      </div>      	
      </div>

      <div>
	      <form action="/admin/users/action" method="post" novalidate enctype="multipart/form-data" id="form">
				 
					  <div class="row">
					    <div class="col-md-3">
				        <div class="form-floating mb-3">
				          <input name="name" type="text" class="form-control" id="floatingInputName" placeholder="Name" value="<?php if(isset($edit) && $edit){echo ($get->name ?? '');} ?>" required>
				          <label for="floatingInputName">Name</label>
				        </div>	   			    	
					    </div>

						  <div class="col-md-3">
				        <div class="form-floating mb-3">
				          <input name="email" type="email" class="form-control" id="floatingInputEmail" placeholder="E-mail" value="<?php if(isset($edit) && $edit){echo ($get->email ?? '');} ?>" required>
				          <label for="floatingInputEmail">E-mail</label>
				        </div>	   			    	
					    </div>

							<div class="col-md-4">
							    <div class="form-floating mb-0 position-relative">
							        <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="password" value="<?php echo isset($_SESSION['form_password']) ? $_SESSION['form_password'] : ''; ?>" <?php if(!isset($edit) || !$edit){echo 'required';} ?> minlength="6">
							        <label for="floatingPassword">Password</label>
							        <i class="fs-5 bi bi-eye-slash toggle-password"></i>
							    </div>
							    <div class="progress password-progress mt-2">
							        <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
							    </div>
							    <span id="password-strength-text" class="form-text"></span>
							    <br>
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
							

			<div class="col-md-12">
	           <fieldset class="border rounded-2 p-3 mb-4">
	              <legend><h5><i class="bi bi-lock"></i> Module permission:</h5></legend>
	              <p>Check the options that the user will have access permission</p>
	              <div class="row">
					<?php 
						renderPermissionSwitch($permission, "prompts", '<i class="fs-5 bi bi-cpu"></i>', "Prompts");
						renderPermissionSwitch($permission, "categories", '<i class="fs-5 bi bi-funnel"></i>', "Categories");
						renderPermissionSwitch($permission, "prompts_output", '<i class="fs-5 bi bi-globe"></i>', "Prompts Output");
						renderPermissionSwitch($permission, "prompts_tone", '<i class="fs-5 bi bi-chat-heart"></i>', "Prompts Tone");
						renderPermissionSwitch($permission, "prompts_writing", '<i class="fs-5 bi bi-chat-left-quote"></i>', "Prompts Writing");
						renderPermissionSwitch($permission, "customers", '<i class="fs-5 bi bi-people"></i>', "Customers");
						renderPermissionSwitch($permission, "settings", "<i class=\"fs-5 bi bi-gear\"></i>", "Settings");
						renderPermissionSwitch($permission, "sales", '<i class="fs-5 bi bi-receipt"></i>', "Sales");
						renderPermissionSwitch($permission, "credits_packs", '<i class="fs-5 bi bi-box"></i>', "Credits Packs");
						renderPermissionSwitch($permission, "analytics", '<i class="fs-5 bi bi-bar-chart"></i>', "Analytics");
						renderPermissionSwitch($permission, "posts", '<i class="fs-5 bi bi-sticky"></i>', "Posts");
						renderPermissionSwitch($permission, "tags", '<i class="fs-5 bi bi-bookmark-star"></i>', "Tags");
						renderPermissionSwitch($permission, "users", '<i class="fs-5 bi bi-person-gear"></i>', "Users");
						renderPermissionSwitch($permission, "languages", '<i class="fs-5 bi bi-translate"></i>', "Languages");
						renderPermissionSwitch($permission, "pages", '<i class="fs-5 bi bi-file-earmark"></i>', "Pages");
						renderPermissionSwitch($permission, "theme", '<i class="fs-5 bi bi-palette"></i>', "Theme");
						renderPermissionSwitch($permission, "seo", '<i class="fs-5 bi bi-fs-5 binoculars"></i>', "SEO");
						renderPermissionSwitch($permission, "menus", '<i class="fs-5 bi bi-list"></i>', "Menus");
						renderPermissionSwitch($permission, "badwords", '<i class="fs-5 bi bi-shield-slash"></i>', "Badwords");
					?>		              	
	              </div>
	           </fieldset>  								
			</div>
							


<?php
require_once(__DIR__."/../../inc/default-form-footer.php");
require_once(__DIR__."/../../inc/footer.php");
?>