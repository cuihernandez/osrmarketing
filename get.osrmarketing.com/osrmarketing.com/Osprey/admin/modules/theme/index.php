<?php
$module_name = "theme";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-palette fs-3'></i> Theme Skin", $module_name, "");
require_once(__DIR__."/../../helpers/message-session.php");
function decodeUnicodeEscapeSequences($matches) {
    return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
}
$get = $theme->get(1);
$edit = true;
$theme_array = json_decode($get->theme_options, true);
foreach ($theme_array as $key => $value) {
    $theme_array[$key] = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
}

if (!$get) {
    header("location:/admin/" . $module_name);
    die();
}
?>

      <div>
        <form action="/admin/theme/action" method="post" novalidate enctype="multipart/form-data">

          <div class="row">


            <div class="col-md-12">
              <p>You can also customize the appearance of the website with your own CSS by <a href="<?php echo $base_url."/admin/settings#nav-custom-code-css-tab"; ?>">clicking here</a>.</p>
            </div>

            <div class="col-md-12">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-card-image fs-5"></i> Hero/banner section homepage</h5></legend>
                  <div class="row align-middle">

                    <div class="col-lg-2 mb-3">
                    <h6>Fav icon</h6>
                      <i>Size 16x16<br>format: .ico</i>
                      <div class="wrapper-image-preview-form preview-image-header-theme mt-2">
                        <input name="image_fav" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'favUpload')">
                        <img class="img-fluid" id="favUpload" src="<?php echo !empty($get->image_fav) ? $base_url . '/public_uploads/' . $get->image_fav : 'https://placehold.co/600x400?text=16+x+16'; ?>" onerror="this.src='https://placehold.co/600x400?text=16+x+16'">
                      </div>
                    </div>

                    <div class="col-lg-2 mb-3">
                    <h6>Logo</h6>
                      <i>Size 215x70<br>format: png or svg</i>
                      <div class="wrapper-image-preview-form preview-image-header-theme mt-2">
                        <input name="image_logo" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'logoUpload')">
                        <img class="img-fluid" id="logoUpload" src="<?php echo !empty($get->image_logo) ? $base_url . '/public_uploads/' . $get->image_logo : 'https://placehold.co/215x70'; ?>" onerror="this.src='https://placehold.co/215x70'">
                      </div>
                    </div>   

                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                      <h6>Background Hero Image</h6>
                      <i>Size 2600x625,<br>format: jpg or webp</i>
                      <div class="wrapper-image-preview-form preview-image-header-theme mt-2">
                        <input name="image_hero_background" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'heroBackgroundImagePreview')">
                        <img class="img-fluid" id="heroBackgroundImagePreview" src="<?php echo !empty($get->image_hero_background) ? $base_url . '/public_uploads/' . $get->image_hero_background : 'https://placehold.co/2600x625'; ?>" onerror="this.src='https://placehold.co/2600x625'">
                      </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                    <h6>Hero Image</h6>
                    <i>Size 570x583,<br>format: png or webp</i>
                      <div class="wrapper-image-preview-form preview-image-header-theme mt-2">
                        <input name="image_hero" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'heroImagePreview')">
                        <img class="img-fluid" id="heroImagePreview" src="<?php echo !empty($get->image_hero) ? $base_url . '/public_uploads/' . $get->image_hero : 'https://placehold.co/570x583'; ?>" onerror="this.src='https://placehold.co/570x583'">
                      </div>
                    </div>        


                    <div class="col-12 col-md-12 mb-3">
                    
                      <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center"><input name="hero_home_text_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['hero_home_text_color'] ?? '');} ?>"> <span class="ms-2">Text color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="hero_button_text_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['hero_button_text_color'] ?? '');} ?>"> <span class="ms-2">Button text color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="hero_button_background_color1" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['hero_button_background_color1'] ?? '');} ?>"> <span class="ms-2">Button background color1</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="hero_button_background_color2" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['hero_button_background_color2'] ?? '');} ?>"> <span class="ms-2">Button background color2</span></li>
                         <li class="list-group-item d-flex align-items-center"><input name="hero_button_background_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['hero_button_background_color_hover'] ?? '');} ?>"> <span class="ms-2">Button background color hover</span></li>
                      </ul>                  
                    </div>                                                         

                  </div>
              </fieldset>               
            </div>

          </div>

          <div class="row">
            
            <div class="col-md-4">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-hr fs-5"></i> Header</h5></legend>
                  <div class="row align-middle">

                    <div class="col">
                      <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center"><input name="header_background_color1" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_background_color1'] ?? '');} ?>"> <span class="ms-2">Background color1</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="header_background_color2" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_background_color2'] ?? '');} ?>"> <span class="ms-2">Background color2</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="header_border_bottom" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_border_bottom'] ?? '');} ?>"> <span class="ms-2">Header border bottom color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="header_inner_page_background_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_inner_page_background_color'] ?? '');} ?>"> <span class="ms-2">Header Inner page background color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="header_inner_page_text_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_inner_page_text_color'] ?? '');} ?>"> <span class="ms-2">Header Inner page text color</span></li>                 
                      </ul>                  
                    </div>

                  </div>
              </fieldset>               
            </div>   

            <div class="col-md-4">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-hr fs-5"></i> Footer</h5></legend>
                  <div class="row align-middle">

                    <div class="col">
                      <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center"><input name="footer_background_color1" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['footer_background_color1'] ?? '');} ?>"> <span class="ms-2">Background color1</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="footer_background_color2" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['footer_background_color2'] ?? '');} ?>"> <span class="ms-2">Background color2</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="footer_text_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['footer_text_color'] ?? '');} ?>"> <span class="ms-2">Footer text color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="footer_text_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['footer_text_color_hover'] ?? '');} ?>"> <span class="ms-2">Footer text color hover</span></li>
                      </ul>                  
                    </div>

                  </div>
              </fieldset>               
            </div>

            <div class="col-md-4">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-list fs-5"></i> Menu</h5></legend>
                  <div class="row align-middle">

                    <div class="col">
                      <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center"><input name="header_menu_links_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_menu_links_color'] ?? '');} ?>"> <span class="ms-2">Text color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="header_menu_links_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_menu_links_color_hover'] ?? '');} ?>"> <span class="ms-2">Text color hover</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="header_menu_links_color_effect_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['header_menu_links_color_effect_hover'] ?? '');} ?>"> <span class="ms-2">Hover effect color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="mobile_background_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['mobile_background_color'] ?? '');} ?>"> <span class="ms-2">Background color mobile menu</span></li> 
                        <li class="list-group-item d-flex align-items-center"><input name="mobile_btn_close_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['mobile_btn_close_color'] ?? '');} ?>"> <span class="ms-2">Button close menu (mobile)</span></li>                            
                      </ul>                  
                    </div>

                  </div>
              </fieldset>               
            </div>


            <div class="col-md-4">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-box-arrow-in-right fs-5"></i> Sign up Button</h5></legend>
                  <div class="row align-middle">

                    <div class="col">
                      <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_up_background_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_up_background_color'] ?? '');} ?>"> <span class="ms-2">Background color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_up_background_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_up_background_color_hover'] ?? '');} ?>"> <span class="ms-2">Background color hover</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_up_text_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_up_text_color'] ?? '');} ?>"> <span class="ms-2">Text color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_up_text_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_up_text_color_hover'] ?? '');} ?>"> <span class="ms-2">Text color hover</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_up_border_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_up_border_color'] ?? '');} ?>"> <span class="ms-2">Border color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_up_border_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_up_border_color_hover'] ?? '');} ?>"> <span class="ms-2">Border color hover</span></li>
                      </ul>                  
                    </div>

                  </div>
              </fieldset>               
            </div>
            <div class="col-md-4">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-person-circle fs-5"></i> Sign in Button</h5></legend>
                  <div class="row align-middle">

                    <div class="col">
                      <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_in_background_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_in_background_color'] ?? '');} ?>"> <span class="ms-2">Background color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_in_background_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_in_background_color_hover'] ?? '');} ?>"> <span class="ms-2">Background color hover</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_in_text_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_in_text_color'] ?? '');} ?>"> <span class="ms-2">Text color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_in_text_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_in_text_color_hover'] ?? '');} ?>"> <span class="ms-2">Text color hover</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_in_border_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_in_border_color'] ?? '');} ?>"> <span class="ms-2">Border color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_sign_in_border_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_sign_in_border_color_hover'] ?? '');} ?>"> <span class="ms-2">Border color hover</span></li>
                      </ul>                  
                    </div>

                  </div>
              </fieldset>               
            </div>

            <div class="col-md-4">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-person-circle fs-5"></i> Primary Button (blue)</h5></legend>
                  <div class="row align-middle">

                    <div class="col">
                      <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center"><input name="btn_primary_background_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_primary_background_color'] ?? '');} ?>"> <span class="ms-2">Background color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_primary_background_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_primary_background_color_hover'] ?? '');} ?>"> <span class="ms-2">Background color hover</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_primary_text_color" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_primary_text_color'] ?? '');} ?>"> <span class="ms-2">Text color</span></li>
                        <li class="list-group-item d-flex align-items-center"><input name="btn_primary_text_color_hover" type="color" class="form-control form-control-color" value="<?php if(isset($edit) && $edit){echo ($theme_array['btn_primary_text_color_hover'] ?? '');} ?>"> <span class="ms-2">Text color hover</span></li>
                        
                      </ul>                  
                    </div>

                  </div>
              </fieldset>               
            </div>            

            <div class="col-md-4">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-person-circle fs-5"></i> AI Card style</h5></legend>
                  <div class="row">
                    <div class="col-12">
                      <select name="ai_card_style" class="form-control">
                        <option value="card-ai-theme-1" <?php if (isset($edit) && $edit && ($theme_array['ai_card_style'] ?? '') === 'card-ai-theme-1') { echo 'selected'; } ?>>Card Theme 1</option>
                        <option value="card-ai-theme-2" <?php if (isset($edit) && $edit && ($theme_array['ai_card_style'] ?? '') === 'card-ai-theme-2') { echo 'selected'; } ?>>Card Theme 2</option>
                      </select>                      
                    </div>
                    <div class="col-12">
                        <img src="<?php echo $base_url; ?>/admin/img/card-style.jpg" class="img-fluid">
                    </div>

                  </div>
              </fieldset>               
            </div>

          </div>

          <div class="row">

            <div class="col-md-12">
              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-card-image fs-5"></i> Sign in, sign up and forgot password images</h5></legend>
                  <div class="row align-middle">

                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                      <h6>Background Sign in</h6>
                      <i>Size 1300x1080<br>format: jpg or webp</i>
                      <div class="wrapper-image-preview-form mt-2">
                        <input name="image_sign_in" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'backgroundSignIn')">
                        <img class="img-fluid" id="backgroundSignIn" src="<?php echo !empty($get->image_sign_in) ? $base_url . '/public_uploads/' . $get->image_sign_in : '#'; ?>" onerror="this.src='<?php echo $base_url; ?>/img/placeholder_1300_1080.jpg'">
                      </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                      <h6>Background Sign up</h6>
                      <i>Size 1300x1080<br>format: jpg or webp</i>
                      <div class="wrapper-image-preview-form mt-2">
                        <input name="image_sign_up" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'backgroundSignUp')">
                        <img class="img-fluid" id="backgroundSignUp" src="<?php echo !empty($get->image_sign_up) ? $base_url . '/public_uploads/' . $get->image_sign_up : '#'; ?>" onerror="this.src='<?php echo $base_url; ?>/img/placeholder_1300_1080.jpg'">
                      </div>
                    </div>  

                    <div class="col-lg-4 col-md-4 col-12 mb-3">
                      <h6>Background Forgot password</h6>
                      <i>Size 1300x1080<br>format: jpg or webp</i>
                      <div class="wrapper-image-preview-form mt-2">
                        <input name="image_fpassword" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'backgroundForgotPassword')">
                        <img class="img-fluid" id="backgroundForgotPassword" src="<?php echo !empty($get->image_fpassword) ? $base_url . '/public_uploads/' . $get->image_fpassword : '#'; ?>" onerror="this.src='<?php echo $base_url; ?>/img/placeholder_1300_1080.jpg'">
                      </div>
                    </div>                            
                                                      

                  </div>
              </fieldset>               
            </div>

          </div>          


          <div class="d-grid">
            <button class="btn btn-success text-uppercase fw-bold mb-2 submit-button" type="submit">Save</button>
          </div>

         <input type="hidden" name="id" value="1">
         <input type="hidden" name="action" value="edit">
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