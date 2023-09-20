<?php
$module_name = "prompts";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-cpu fs-3'></i> Prompts", $module_name, "Add new AI");
$get = $prompts->getList();
require_once(__DIR__."/../../helpers/message-session.php");
?>

      <div class="modal" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Modal title</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Modal body text goes here.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modalCopyCode" tabindex="-1" aria-labelledby="modalCopyCodeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalCopyCodeLabel">Copy embed code</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>      
            <div class="modal-body">

            <div id="modal-copy-code-body">
              
            </div>

            <div class="alert alert-warning mb-1">
              <ul class="mt-1">
                <li>When sharing a chat on a website:</li>
                <ul>
                  <li>The website user does not need to log in.</li>
                  <li>The user can freely converse with the bot without the need for registration.</li>
                  <li>There are no limits to the conversation.</li>
                  <li>The conversation history will not be saved in a shared chat.</li>
                  <li>It is not possible to access conversations made by users.</li>
                  <li>It is not possible to download text (txt), PDF, or Word (docx) formats.</li>
                  <li>Only the free artificial intelligence can be shared; the VIP artificial intelligence cannot be shared.</li>
                </ul>
              </ul>
            </div>
              
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" onclick="copyContent('modal-copy-code-body')"><i class="bi bi-clipboard"></i> Copy code</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Close</button>
            </div>
          </div>
        </div>
      </div>


      <div class="table-responsive">
        <table class="table table-striped border data-table align-middle">
          <thead>
            <tr>
              <th scope="col" style="width: 1%;"></th>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">Expert</th>
              <th scope="col">Url</th>
              <th scope="col">Temp.</th>
              <th scope="col">Status</th>
              <th scope="col">Model</th>
              <th scope="col">Embed chat</th>
              <th scope="col">Package</th>
              <th scope="col" class="text-end">Action</th>
            </tr>
          </thead>
          <tbody id="sortableTableBody" data-module="<?php echo $module_name; ?>">
            <?php 
            foreach ($get as $show) {
            $getPromptPackage = $prompts_credits_packs->getListByIdPrompt($show->id)->FetchAll();
            $show = array_map(function ($value) {
                return is_string($value) ? stripslashes($value) : $value;
            }, (array) $show);
            $show = (object) $show;
            ?>
            <tr data-id="<?php echo $show->id; ?>">
              <td class="handle" style="cursor: move;">&#9776;</td>
              <td><div class="wrapper-form-img"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $show->image; ?>" onerror="this.src='https://placehold.co/640x700'"></div></td>
              <td><a target="_blank" href="<?php echo $base_url."/admin/".$module_name."/edit/".$show->id; ?>"><?php echo truncateText($show->name,50); ?></a></td>
              <td><?php echo truncateText($show->expert,20); ?></td>
              <td><a href="<?php echo $base_url."/chat/".$show->slug; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-up-right"></i> View</a></td>
              <td><?php echo $show->temperature; ?></td>
              <td>
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch-<?php echo $show->id; ?>" <?php echo ($show->status == 1) ? 'checked' : ''; ?>>
                </div>
              </td>              
              <td><?php echo $show->API_MODEL; ?></td>
              <td>
                <?php if ($show->allow_embed_chat) { ?>
                  <button class="btn btn-success btn-sm btn-show-embed-code" data-slug="<?php echo $show->slug; ?>" data-bs-toggle="modal" data-bs-target="#modalCopyCode"><i class="bi bi-share"></i> Yes, see code</button>
                <?php } else { ?>
                  No
                <?php } ?>
              </td>        
              <td>
              <?php 
              if($getPromptPackage){
                foreach ($getPromptPackage as $showPromptPackage) {
                  $getCreditPack = $credits_packs->get($showPromptPackage->id_credits_pack);
                  if($getCreditPack){
                    echo "<a target='_blank' href='".$base_url."/admin/credits_packs/edit/".$getCreditPack->id."'><span class='badge rounded-pill bg-dark me-1'>".$getCreditPack->name." ".$getCreditPack->price."</span></a>";
                  }else{
                    echo "free";
                  }
                }
              }else{
                echo "free";
              }
              ?>                
              </td>        
              <td class="text-end">
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

