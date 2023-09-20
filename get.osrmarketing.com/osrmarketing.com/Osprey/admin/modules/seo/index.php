<?php
$module_name = "seo";
$use_save_absolute = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-binoculars fs-3'></i> SEO", $module_name, "");
require_once(__DIR__."/../../helpers/message-session.php");
function decodeUnicodeEscapeSequences($matches) {
    return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
}
$get = $seo->get(1);
$edit = true;
$seo_array = json_decode($get->seo_options, true);
foreach ($seo_array as $key => $value) {
    $seo_array[$key] = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
}


if (!$get) {
    header("location:/admin/" . $module_name);
    die();
}
?>

      <div>
        <form action="/admin/seo/action" method="post" novalidate enctype="multipart/form-data">

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Home page</h5></legend>

        <div class="row">

          <div class="col-lg-2 col-md-4">
          <small>590x590</small>
          <div class="wrapper-image-preview-form preview-image-header-seo">
            <input name="image_thumb" type="file" class="form-control" accept="image/*" onchange="loadPreviewImage(event, 'logoUpload')">
            <img class="img-fluid" id="logoUpload" src="<?php echo !empty($get->image_thumb) ? $base_url . '/public_uploads/' . $get->image_thumb : '#'; ?>" onerror="this.src='<?php echo $base_url; ?>/img/placeholder_header-image.png'">
          </div>
                  
          </div>

          <div class="col-lg-10 col-md-8">
            <div class="row">
              <div class="col-lg-12">
                <label class="mb-2 col-12">
                  <span>Theme color</span>
                  <input name="theme_color" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['theme_color'] ?? '');} ?>">
                </label>
              </div>

              <div class="col-lg-12">
                <label class="mb-2 col-12">
                  <span>Meta title</span>
                  <input name="home_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['home_meta_title'] ?? '');} ?>">
                </label>
              </div>

              <div class="col-lg-12">
                <label class="mb-2 col-12">
                  <span>Meta description</span>
                  <input name="home_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['home_meta_description'] ?? '');} ?>">
                </label>
              </div>
            </div>
          </div>

        </div>

      </fieldset>

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Ai team page</h5></legend>

        <div class="row">

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title</span>
              <input name="ai_team_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['ai_team_meta_title'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description</span>
              <input name="ai_team_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['ai_team_meta_description'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title (Filter page)</span>
              <input name="ai_team_meta_title_filter" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['ai_team_meta_title_filter'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description (Filter page)</span>
              <input name="ai_team_meta_description_filter" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['ai_team_meta_description_filter'] ?? '');} ?>">
            </label>
          </div>

        </div>

      </fieldset>

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Chat page</h5></legend>

        <div class="row">

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title</span>
              <input name="chat_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['chat_meta_title'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description</span>
              <input name="chat_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['chat_meta_description'] ?? '');} ?>">
            </label>
          </div>

        </div>

      </fieldset>

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Pricing page</h5></legend>

        <div class="row">

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title</span>
              <input name="pricing_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['pricing_meta_title'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description</span>
              <input name="pricing_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['pricing_meta_description'] ?? '');} ?>">
            </label>
          </div>

        </div>

      </fieldset>

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Sign in page</h5></legend>

        <div class="row">

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title</span>
              <input name="sign_in_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['sign_in_meta_title'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description</span>
              <input name="sign_in_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['sign_in_meta_description'] ?? '');} ?>">
            </label>
          </div>

        </div>

      </fieldset>

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Sign up page</h5></legend>

        <div class="row">

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title</span>
              <input name="sign_up_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['sign_up_meta_title'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description</span>
              <input name="sign_up_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['sign_up_meta_description'] ?? '');} ?>">
            </label>
          </div>

        </div>

      </fieldset>

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Maintenance page</h5></legend>

        <div class="row">

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title</span>
              <input name="maintenance_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['maintenance_meta_title'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description</span>
              <input name="maintenance_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['maintenance_meta_description'] ?? '');} ?>">
            </label>
          </div>

        </div>

      </fieldset>

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Blog</h5></legend>

        <div class="row">

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta title</span>
              <input name="blog_meta_title" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['blog_meta_title'] ?? '');} ?>">
            </label>
          </div>

          <div class="col-lg-12">
            <label class="mb-2 col-12">
              <span>Meta description</span>
              <input name="blog_meta_description" type="text" class="form-control" value="<?php if(isset($edit) && $edit){echo ($seo_array['blog_meta_description'] ?? '');} ?>">
            </label>
          </div>

        </div>

      </fieldset>


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