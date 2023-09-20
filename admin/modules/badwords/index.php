<?php
$module_name = "badwords";
$use_save_absolute = true;
$use_codemirror = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-shield-slash fs-3'></i> Bad words", $module_name, "");
require_once(__DIR__."/../../helpers/message-session.php");
$get = $badwords->get(1);
?>


  <div>
    <form action="/admin/badwords/action" method="post" novalidate enctype="multipart/form-data">

      <fieldset class="border rounded-2 p-3 mb-4">
        <legend><h5>Bad Words list</h5></legend>
        <p>Please provide a list of swear words separated by commas, which the chat will filter before sending the words to the API.</p>
        <div class="row align-middle">

          <div class="col-md-12">
            <textarea name="badwords" class="form-control" style="height:500px" placeholder="Bad Words list"><?php echo $get->badwords; ?></textarea>
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