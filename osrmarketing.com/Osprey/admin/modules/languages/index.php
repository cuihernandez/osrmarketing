<?php
$module_name = "languages";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-translate fs-3'></i> Languages (Front website)", $module_name, "Add new language");
$get = $languages->getList();
require_once(__DIR__."/../../helpers/message-session.php");
?>

      <div class="alert alert-light">
        <h6 class="mb-0"><i class="bi bi-info-circle-fill"></i> You can only select 1 language at a time. If you don't select any language, English will be the default. The language is only for the front of the website</h6>
      </div>

      <div class="table-responsive">
        <table class="table table-striped border align-middle">
          <thead>
            <tr>
              <th scope="col" style="width: 1%;"></th>
              <th scope="col">Language</th>
              <th scope="col">Use this language (Front website)</th>
              <th scope="col" class="text-end">Action</th>
            </tr>
          </thead>
          <tbody id="sortableTableBody" data-module="<?php echo $module_name; ?>">
            <?php foreach ($get as $show) {?>
            <tr data-id="<?php echo $show->id; ?>">
              <td class="handle" style="cursor: move;">&#9776;</td>
              <td><?php echo $show->lang_name." - ".$show->lang; ?></td>
              <td>
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input check-language" type="checkbox" role="switch" id="statusSwitch-<?php echo $show->id; ?>" <?php echo ($show->isDefault == 1) ? 'checked' : ''; ?>>
                </div>
              </td>
              <td class="text-end">
                <?php if(!$show->forbid_delete){?>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-module="<?php echo $module_name; ?>"  data-id="<?php echo $show->id; ?>"><i class="bi bi-trash-fill fs-6"></i></button>
                <?php } ?>
                <a href="<?php echo $base_url."/admin/".$module_name."/edit/".$show->id; ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square fs-6"></i></a>
              </td>
            </tr>
            <?php } ?>
          
          </tbody>
        </table>

      </div>    

<?php
require_once("../../inc/footer.php");
?>