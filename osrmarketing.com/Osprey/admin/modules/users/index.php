<?php
$module_name = "users";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-person-gear fs-3'></i> Admin users", $module_name, "Add new admin user");
$get = $users->getList();
require_once(__DIR__."/../../helpers/message-session.php");
?>

      <div class="table-responsive">
        <table class="table border align-middle">
          <thead>
            <tr>
              <th scope="col" style="width: 1%;"></th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-end">Action</th>
            </tr>
          </thead>
           <tbody id="sortableTableBody" data-module="<?php echo $module_name; ?>">
            <?php foreach ($get as $show) {?>
             <tr data-id="<?php echo $show->id; ?>">
              <td class="handle" style="cursor: move;">&#9776;</td>
              <td class="align-middle"><?php echo $show->name; ?></td>
              <td class="align-middle"><?php echo $show->email; ?></td>
              <td class="align-middle">
                <?php if(!$show->forbid_delete){?>
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch-<?php echo $show->id; ?>" <?php echo ($show->status == 1) ? 'checked' : ''; ?>>
                </div>
                <?php } else echo "<span class='badge text-bg-danger'>Cannot be deleted</span>"; ?>
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