<?php
$module_name = "customers";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-people fs-3'></i> Customers", $module_name, "");
$get = $customers->getList();
require_once(__DIR__."/../../helpers/message-session.php");
?>

      <div class="table-responsive">
        <table class="table table-striped border data-table align-middle">
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">E-mail</th>
              <th scope="col">Messages</th>
              <th scope="col">Threads</th>
              <th scope="col">Credits</th>              
              <th scope="col">Active</th>
              <th scope="col" data-order-by="order-col">Registration date</th>
              <th scope="col">Login as user</th>
              <th scope="col" class="text-end" width="350">Action</th>
            </tr>
          </thead>
          <tbody id="sortableTableBody" data-module="<?php echo $module_name; ?>">
            <?php foreach ($get as $show) {?>
            <tr data-id="<?php echo $show->id; ?>">
              <td><?php echo $show->name; ?></td>
              <?php if($config->demo_mode){?>
              <td><span class="badge bg-danger">Email will not be shown in demo mode</span></td>
              <?php }else{ ?>   
              <td><?php echo $show->email; ?></td>
              <?php } ?>
              <td><?php echo $show->total_messages; ?></td>
              <td><?php echo $show->total_threads; ?></td>
              <td><?php echo number_format($show->credits, 0, ',', '.'); ?></td>
              <td>
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch-<?php echo $show->id; ?>" <?php echo ($show->status == 1) ? 'checked' : ''; ?>>
                </div>
              </td>              
              <td><?php echo $show->created_at; ?></td>
              <td><a target="_blank" href="<?php echo $base_url."/admin/customers/login/".$show->id; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i> Login as user</a></td>

              <td class="text-end">
                <a href="<?php echo $base_url; ?>/admin/<?php echo $module_name; ?>/view-purchases/<?php echo $show->id; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-receipt"></i> Purchases (<?php echo $show->total_purchases; ?>)</a>
                <a href="<?php echo $base_url; ?>/admin/<?php echo $module_name; ?>/view-chat/<?php echo $show->id; ?>" class="btn btn-secondary btn-sm"><i class="bi bi-chat-left-text"></i> Chats (<?php echo $show->total_messages; ?>)</a>
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