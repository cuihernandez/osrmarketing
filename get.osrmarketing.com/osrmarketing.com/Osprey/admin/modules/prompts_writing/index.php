<?php
$module_name = "prompts_writing";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-chat-left-quote fs-3'></i> Writing style", $module_name, "Add new prompt writing");
$get = $prompts_writing->getList();
require_once(__DIR__."/../../helpers/message-session.php");
?>

      <div class="table-responsive">
        <table class="table table-striped border data-table">
          <thead>
            <tr>
              <th scope="col" style="width: 1%;"></th>
              <th scope="col">Name</th>
              <th scope="col">Value</th>
              <th scope="col">Status</th>
              <th scope="col" style="text-align: right;">Action</th>
            </tr>
          </thead>
          <tbody id="sortableTableBody" data-module="prompts_writing">
            <?php foreach ($get as $show) {?>
            <tr data-id="<?php echo $show->id; ?>">
              <td class="handle" style="cursor: move;">&#9776;</td>
              <td class="align-middle"><?php echo $show->name; ?></td>
              <td class="align-middle"><?php echo $show->value; ?></td>
              <td class="align-middle">
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch-<?php echo $show->id; ?>" <?php echo ($show->status == 1) ? 'checked' : ''; ?>>
                </div>
              </td>    
              <td class="align-middle" style="text-align: right;">
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-module="<?php echo $module_name; ?>"  data-id="<?php echo $show->id; ?>"><i class="bi bi-trash-fill fs-6"></i></button>
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