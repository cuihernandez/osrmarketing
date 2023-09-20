<?php
$module_name = "credits_packs";
$use_sortable = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-box fs-3'></i> Credits pack", $module_name, "Add new credit package");
$get = $credits_packs->getList()->FetchAll();
require_once(__DIR__."/../../helpers/message-session.php");
checkDuplicateTiers($get);

$getCreditsPacks = $credits_packs->getListFront()->FetchAll();
if($config->display_prompts_packagelist){
  $highTier = $config->vip_higher_tier;
  $organized_vip_prompts = array();
  $free_prompts = array_map(function($prompt) {
      return array(
          'id' => $prompt->id,
          'name' => $prompt->name,
          'image' => $prompt->image
      );
  }, $prompts_credits_packs->getAllFreePrompts()->FetchAll());

  foreach ($getCreditsPacks as $showCreditsPackArray) {
      $vip_prompts = array_map(function($showP) {
          return array(
              'id' => $showP->id,
              'name' => $showP->name,
              'image' => $showP->image,
              'tier' => $showP->tier
          );
      }, $prompts_credits_packs->getAllFreePromptsByCreditPackId($showCreditsPackArray->id)->FetchAll());
    
      foreach ($vip_prompts as $vip_prompt) {
          $organized_vip_prompts[$vip_prompt['tier']][] = $vip_prompt;
      }
  }

  if ($highTier) {
      $all_tiers = array_keys($organized_vip_prompts);
      sort($all_tiers);

      $accumulated_prompts = array();
      foreach ($all_tiers as $i) {
          if (!empty($organized_vip_prompts[$i])) {
              // Merge and remove duplicates
              $merged = array_merge($accumulated_prompts, $organized_vip_prompts[$i]);
              $unique = array();
              foreach ($merged as $item) {
                  $unique[md5($item['name'].$item['image'])] = $item;
              }
              $organized_vip_prompts[$i] = array_values($unique);
          }
          $accumulated_prompts = $organized_vip_prompts[$i] ?? array();
      }
  }

foreach ($organized_vip_prompts as $tier => &$prompts) {
    if ($config->vip_display_free_prompts) {
        $free_prompts_tiered = array_map(function($prompt) use ($tier) {
            $prompt['tier'] = $tier;
            return $prompt;
        }, $free_prompts);

        // Merge and remove duplicates
        $merged = array_merge($free_prompts_tiered, $prompts);
        $unique = array();
        foreach ($merged as $item) {
            $unique[md5($item['name'].$item['image'])] = $item;
        }
        $prompts = array_values($unique);
    } else {
        $prompts = $prompts;
    }
}
  unset($prompts);
}
?>

      <div class="table-responsive">
        <table class="table table-striped border data-table align-middle">
          <thead>
            <tr>
              <th scope="col" style="width: 1%;"></th>
              <th scope="col">Icon</th>
              <th scope="col">Tier</th>
              <th scope="col">Name</th>
              <th scope="col">Price (Label)</th>
              <th scope="col">Currency</th>
              <th scope="col">Amount (Api price)</th>
              <th scope="col">Credit</th>
              <th scope="col">Description</th>
              <th scope="col" width="300">Prompts</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-end">Action</th>
            </tr>
          </thead>
          <tbody id="sortableTableBody" data-module="<?php echo $module_name; ?>">
            <?php foreach ($get as $show) {?>
            <tr data-id="<?php echo $show->id; ?>">
              <td class="handle" style="cursor: move;">&#9776;</td>
              <td><div class="wrapper-form-img-icon"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $show->image; ?>" onerror="this.src='https://placehold.co/256x256'"></div></td>
              <td><?php echo $show->tier; ?></td>
              <td><?php echo $show->name; ?></td>
              <td><?php echo $show->price; ?></td>
              <td><?php echo $show->currency_code; ?></td>
              <td><?php echo $show->amount; ?></td>
              <td><?php echo $show->credit; ?></td>
              <td>
                <ul>
                <?php

                $desc = json_decode($show->description); 
                  foreach ($desc as $showDescription) {
                    echo "<li>".$showDescription."</li>";
                  }
                ?>
                </ul>
                </td>
              <td>
              <div class="package-wrapper-prompt">
                <?php
                if (isset($organized_vip_prompts[$show->tier])) {
                  foreach ($organized_vip_prompts[$show->tier] as $prompt) {?>
                      <div class="package-wrapper-prompt-thumb">
                        <img src="<?php echo $base_url."/public_uploads/".$prompt['image']; ?>" alt="<?php echo $prompt['name']; ?>" title="<?php echo $prompt['name']; ?>" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'">
                      </div>
                  <?php } ?>
                <?php } ?>
              </div>
              </td>
              <td>
                <div class="form-check form-switch form-switch-lg">
                  <input class="form-check-input" type="checkbox" role="switch" id="statusSwitch-<?php echo $show->id; ?>" <?php echo ($show->status == 1) ? 'checked' : ''; ?>>
                </div>
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