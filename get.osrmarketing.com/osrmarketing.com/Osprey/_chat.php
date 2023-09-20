<?php
// Initializing variables and requiring necessary files
$no_footer = true;
$header_min = true;
$loadAI = true;
$use_bootstrap_icons = true;
$mobile_bg = true;
$embed_chat = false;
$vip = true;
require_once("inc/includes.php");

// Redirect to 404 page if the slug is not set or if the AI does not exist
if (!isset($_REQUEST['slug']) || !($AI = $prompts->getBySlug($_REQUEST['slug']))) {
    header("location:/404");
    exit();
}

// Check user status if logged in
if ($isLogged && !$getCustomer->status) {
    redirect($base_url.'/panel', $lang['customer_disable_message'], 'error');
}

checkCustomerConfirmEmail($isLogged);

// Fetch necessary AI details
$AI_ID = $AI->id;
$getListAI = $prompts->getListFront();
$getPromptsOutput = $prompts_output->getListFront();
$getPromptsOutputCount = $getPromptsOutput->rowCount();
$getCategories = $prompts_categories->getListByIdPrompt($AI->id);

// Fetch AI tone details
$getPromptsTone = $prompts_tone->getListFront();
$getPromptsToneCount = $getPromptsTone->rowCount();

// Fetch AI writing details
$getPromptsWriting = $prompts_writing->getListFront();
$getPromptsWritingCount = $getPromptsWriting->rowCount();


$credit_packages_ids = array();
$required_credit_pack = array();


//Lista os requisitos minimos para ter acesso a esse bot
$getVipByIdPrompt = $prompts->checkVipByIdPrompt($AI_ID);
foreach ($getVipByIdPrompt as $showVipByIdPrompt) {
    $getTierCreditPack = $credits_packs->get($showVipByIdPrompt->id_credits_pack);

    if(is_object($getTierCreditPack) && property_exists($getTierCreditPack, 'status')){
        if($getTierCreditPack->status){
            $required_credit_pack[] = array(
                'tier' => $getTierCreditPack->tier,
                'id_credits_pack' => $showVipByIdPrompt->id_credits_pack
            );
        }
    }

}

$required_tiers = array_column($required_credit_pack, 'tier');
if (empty($required_credit_pack)) {
    $vip = false;
}else{
    foreach($required_tiers as $required_tier) {
        foreach($user_credit_pack as $user_pack) {
            if(($config->vip_higher_tier == 1 ? $user_pack['tier'] >= $required_tier : $user_pack['tier'] == $required_tier)) {
                $vip = false;
                echo $vip;
                break;
            }
        }
    }
}

if (isset($_GET['embed_chat']) && $_GET['embed_chat'] && $AI->allow_embed_chat) {
    $embed_chat = true;
    $no_header = true;
} elseif (isset($_GET['embed_chat']) && $_GET['embed_chat'] && !$AI->allow_embed_chat) {
    die($lang['chat_embedding_not_allowed_message']);
}

// Handling chat request
if (isset($_GET['chat'])) {
    $getTargetThread = $_GET['chat'];
    $checkAIThread = $messages->getByThread($getTargetThread)->Fetch();

    if (isset($checkAIThread->id) && $checkAIThread->id) {
        if (!($checkAIThread->id_prompt == $AI->id && $checkAIThread->id_customer == @$_SESSION['id_customer'])) {
            header("location:".$base_url."/");
            exit();
        }
    } else {
        header("location:".$base_url."/");
        exit();
    }

    $_SESSION['threads'][$AI->id] = $getTargetThread;
}

define('META_TITLE', $seoConfig['chat_meta_title']." ".$AI->name);
define('META_DESCRIPTION', $seoConfig['chat_meta_description']." ".$AI->name);

// Include chat session script
require_once("inc/header.php");
require_once("modules/customer/chat-session.php");

if (isset($AI->suggestions) && $AI->suggestions) {
    $getSuggestions = json_decode($AI->suggestions, true);
}
?>

<?php if ($getSuggestions && $AI->display_suggestions) : ?>
    <div class="modal fade" tabindex="-1" id="modalSuggestion">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-lightbulb"></i> <?php echo $lang['questions_suggestions_title']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $lang['button_close_modal']; ?>"></button>
                </div>
                <div class="modal-body">
                    <div class="wrapper-suggestion-group">
                        <ul class="list-group">
                            <?php foreach ($getSuggestions as $showSuggestions) : ?>
                                <li class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                    <p><?php echo $showSuggestions; ?></p>
                                    <span class="btn btn-primary lg-ms-3 md-mt-1 use-suggestion w-20"><i class="bi bi-arrow-right-square"></i> <?php echo $lang['btn_use_suggestion']; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $lang['button_close_modal']; ?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($AI->display_description) : ?>
    <div class="modal fade" tabindex="-1" id="modalDefault">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $AI->name; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $lang['button_close_modal']; ?>"></button>
                </div>
                <div class="modal-body pre-line"><?php echo trim($AI->description); ?></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo $lang['button_close_modal']; ?></button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!$isLogged) : ?>
    <div class="modal fade" tabindex="-1" id="modalDemo">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $lang['create_account_to_continue_title']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $lang['button_close_modal']; ?>"></button>
                </div>
                <div class="modal-body">
                    <?php echo $lang['create_account_to_continue_text']; ?>
                </div>
                <div class="modal-footer">
                    <a href="<?php echo $base_url; ?>/sign-up" class="btn btn-success"><?php echo $lang['sign_up']; ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
if (isset($_SESSION['action']) && !empty($_SESSION['action'])) {
    if ($_SESSION['action'] === 'success') {
        echo '<div class="container pt-lg-3"><div class="row"><div class="alert alert-success"><i class="bi bi-check2-circle"></i> ' . $_SESSION['action_message'] . '</div></div></div>';
    } else {
        echo '<div class="container pt-lg-3"><div class="row"><div class="alert alert-danger"><i class="bi bi-exclamation-octagon"></i> ' . $_SESSION['action_message'] . '</div></div></div>';
    }
}
?>


  <section id="chat-background" class="<?php if($embed_chat) echo "embed-chat"; ?>">
    <div class="container pt-lg-2 pb-lg-4 <?php if (isset($config->chat_full_width) && $config->chat_full_width) echo 'chat-full-width'; ?>">
      <div class="row chat-background">

        <?php if($AI->display_contacts_user_list && !$embed_chat){?>
        <div class="col-lg-3 col-md-3 col-sm12 p-0 col-contacts-border" style="display:none;">
         
         <div class="ai-contacts-top">
           <strong><?php echo $lang['chat_call_action1']; ?></strong>
           <span><?php echo $lang['chat_call_action2']; ?></span>
         </div>

         <div class="ai-contacts-scroll">
            <?php 
            $itemCount = 0;
            $getListAI = reorderArrayById($getListAI, $AI->id);
            foreach ($getListAI as $showListAI) {?>
              <a href="<?php echo $showListAI->slug; ?>">
                <div class="ai-contacts-item <?php if($itemCount == 0) echo 'ai-contacts-item-active';?>">
                  <?php showVipCard($showListAI->id); ?>
                  <div class="ai-contacts-image"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $showListAI->image;?>" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'" alt="<?php echo $showListAI->name;?>" title="<?php echo $showListAI->name;?>"></div>
                  <div class="ai-contacts-info">
                    <div class="ai-contacts-name"><?php echo $showListAI->name;?></div>
                    <div class="ai-contacts-job" alt="<?php echo $showListAI->expert;?>"><?php echo $showListAI->expert;?></div>
                  </div>
                </div>
              </a>
            <?php  $itemCount++; } ?>
         </div>

        </div>
        <?php } ?>

        <div class="col p-0 col-main-chat">

         <div class="ai-chat-top">
          <div class="row align-items-center">
            <div class="col-md-7 col-lg-8 col-10">
              <div class="wrapper-ai-chat-top">
                <div class="ai-chat-top-image"><img src="<?php echo $base_url; ?>/public_uploads/<?php echo $AI->image;?>" alt="image" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'"></div>
                <div class="ai-chat-top-info">
                  <div class="ai-chat-top-name"><h4><?php echo $AI->name;?> <span class="online-bullet"></span> <?php showVipCard($AI_ID); ?></h4></div>
                  <div class="ai-chat-top-job"><?php echo $AI->expert;?></div>
                    <div class="ai-categories">
                    <?php if($AI->display_API_MODEL){?>
                    <span class="badge bg-success badge-categories"><?php echo $AI->API_MODEL; ?></span>
                    <?php } ?>
                    <?php 
                    if(!$embed_chat){
                        foreach ($getCategories as $showCategories) {
                          $categoriesName = $categories->get($showCategories->id_category);
                          echo "<a class='badge bg-dark badge-categories' href='$base_url/ai-team/$categoriesName->slug'>$categoriesName->name</a>";
                        }
                    }
                    ?>
                    </div>                  
                </div>
              </div>
            </div>

            <div class="col-md-5 col-lg-4 col-2">

            <div class="icons-options">
               
                <div class="dropdown-center">

                <?php if($AI->display_share){?>
                <div class="chat-action-buttons chat-btn-share op-0" onclick="shareChat('<?php echo $isLogged ? $base_url."/share/".@$_SESSION['threads'][$AI->id] : 'not logged'; ?>');">
                    <i class="bi bi-share" alt="<?php echo $lang['share']; ?>" title="<?php echo $lang['share']; ?>"></i>
                </div>
                <?php } ?>

                  <?php if($getSuggestions && $AI->display_suggestions){?>
                  <div class="chat-action-buttons chat-btn-suggestions" data-bs-toggle="modal" data-bs-target="#modalSuggestion">
                    <i class="bi bi-lightbulb" alt="<?php echo $lang['btn_suggestions']; ?>" title="<?php echo $lang['btn_suggestions']; ?>"></i>
                  </div>
                  <?php } ?>
                  
                  <?php if($AI->display_contacts_user_list && !$embed_chat){?>
                  <div class="chat-action-buttons toggle_employees_list">
                     <i class="bi bi-person-lines-fill" alt="<?php echo $lang['btn_employees_list']; ?>" title="<?php echo $lang['btn_employees_list']; ?>"></i>
                  </div>
                  <?php } ?>
                  
                  <?php if($AI->display_description){?>
                  <div class="chat-action-buttons chat-btn-about" data-bs-toggle="modal" data-bs-target="#modalDefault">
                    <i class="bi bi-info-circle" alt="<?php echo $lang['btn_about']; ?>" title="<?php echo $lang['btn_about']; ?>"></i>
                  </div>
                  <?php } ?>
                    <div class="chat-action-buttons btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-gear-fill" alt="<?php echo $lang['btn_config']; ?>" title="<?php echo $lang['btn_config']; ?>"></i>
                    </div>

                      <ul class="dropdown-menu dropdown-item-chat" aria-labelledby="dropdownMenuButton">
                        <li id="new-chat"><a class="dropdown-item" href="<?php echo $base_url; ?>/new-chat/<?php echo $AI->slug . ($embed_chat == 1 ? '?embed=true' : ''); ?>"><span><i class="bi bi-plus-circle"></i> <?php echo $lang['button_new_chat']; ?></span></a></li>
                        <?php if(!$embed_chat){?>
                        <li id="close-chat" class="d-block d-lg-none"><a class="dropdown-item" href="<?php echo $base_url;?>/ai-team"><i class="bi bi-x-lg"></i><?php echo $lang['button_close']; ?></span></a></li> 
                        <?php } ?>
                        <li data-bs-toggle="modal" data-bs-target="#modalSuggestion" class="d-block d-lg-none"><a class="dropdown-item" href="#"><i class="bi bi-lightbulb"></i><?php echo $lang['btn_suggestions']; ?></span></a></li>
                        <li data-bs-toggle="modal" data-bs-target="#modalDefault" class="d-block d-lg-none"><a class="dropdown-item" href="#"><i class="bi bi-file-person"></i><?php echo $lang['btn_about']." ".$AI->name; ?></span></a></li> 
                        <?php if(!$embed_chat){?>
                        <li class="d-block d-lg-none"><a class="dropdown-item" href="<?php echo $base_url;?>/ai-team"><i class="bi bi-people"></i><?php echo $lang['btn_employees_list'];?></span></a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_url."/download-chat/".$AI->slug."/".@$_SESSION['threads'][$AI->id]; ?>?format=txt"><span><i class="bi bi-filetype-txt"></i> <?php echo $lang['button_download_chat']; ?></span></a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_url."/download-chat/".$AI->slug."/".@$_SESSION['threads'][$AI->id]; ?>?format=pdf"><span><i class="bi bi-filetype-pdf"></i> <?php echo $lang['button_download_chat_pdf']; ?></span></a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_url."/download-chat/".$AI->slug."/".@$_SESSION['threads'][$AI->id]; ?>?format=docx"><span><i class="bi bi-filetype-docx"></i> <?php echo $lang['button_download_chat_docx']; ?></span></a></li>
                        <?php } ?>
                      </ul>

                </div>
              
              </div>
            </div>

          </div>
         </div>
        



      <?php if(isset($vip) && $vip){?>
        <div class="wrapper-vip-message">
            <h3><?php echo $lang['vip_upgrade_message'];?></h3>
                <div class="wrapper-vip-package-list">
                    <?php 
                    // Get all credit packs
                    $creditPackages = [];
                    if (!is_null($required_credit_pack)) {
                        foreach ($required_credit_pack as $credit_pack_id) {
                            $creditPackages[] = $credits_packs->get($credit_pack_id['id_credits_pack']);
                        }
                    }
                    
                    // Comparison function
                    function compare($a, $b) {
                        if ($a->amount == $b->amount) {
                            return 0;
                        }
                        return ($a->amount < $b->amount) ? -1 : 1;
                    }

                    // Sort the credit packs
                    usort($creditPackages, 'compare');

                    // Show credit packs
                    foreach ($creditPackages as $getCreditPackInfo) {
                        ?>
                        <div class="wrapper-vip-credit-pack">
                            <div class="wrapper-vip-credit-image">
                                <img src="<?php echo $base_url."/public_uploads/".$getCreditPackInfo->image; ?>" alt="<?php echo $getCreditPackInfo->name; ?>" title="<?php echo $getCreditPackInfo->name; ?>" onerror="this.src='<?php echo $base_url; ?>/img/no-image.svg'">
                            </div>
                            <div class="wrapper-vip-credit-info">
                                <h4><?php echo $getCreditPackInfo->name; ?></h4>
                                <h5><?php echo $getCreditPackInfo->price; ?></h5>
                            </div>
                        </div>
                        <?php 
                    }
                    ?>
                </div>
            <div class="text-center mt-2">
                <a href="/pricing" class="btn btn-primary"><?php echo $lang['vip_check_plans_btn']; ?></a>
            </div>            
        </div>
        <?php } ?>    
        <?php if(isset($vip) && !$vip){?>
        <div class="ia-chat-content">
          <div class="row">
            <div class="cell">
              <div class="chat-frame">
                <?php
                function displayMessage($role, $name, $content, $dall_e_array, $datetime, $image = null, $display_avatar = false, $use_google_voice = false, $extra_class = '') {
                    global $lang;
                    global $base_url;
                    global $AI;
                    
                    $isImageBlock = false;
                    $json_array = json_decode($dall_e_array ?? '', true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($json_array['data'])) {
                        $content = '<p><strong class="ia-image-prompt-label">'.$content.'</strong></p><div class="wrapper-image-ia image_ia_' . time() . '">';
                        foreach ($json_array['data'] as $item) {
                            if (isset($item['url'])) {
                                $imageName = $item['url'];
                                $content .= '<div class="image-ia"><img onerror="this.src=\'' . $base_url . '/img/no-image.svg\'" src="' . $base_url . '/public_uploads/dalle/' . $imageName . '"></div>';
                                $isImageBlock = true;
                            }
                        }
                        $content .= '</div>';
                    }
                    ?>

                      <?php if($content){?>
                        <div class="conversation-thread <?php echo $role == 'assistant' ? 'thread-ai' : 'thread-user'; ?> <?php echo $extra_class; ?>">
                            <?php if ($display_avatar && $role == 'assistant') { ?>
                                <div class="user-image"><img onerror="this.src='<?php echo $base_url;?>/img/no-image.svg'" src="<?php echo $base_url;?>/public_uploads/<?php echo $image; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>"></div>
                            <?php } ?>
                            <div class="message-container">
                                <div class="message-info">
                                    <div class="wrapper-chat-header">
                                        <div class="user-name"><h5><?php echo $role == 'assistant' ? $name : $lang['you']; ?></h5></div>
                                        <div class="chat-actions">
                                            <?php if (!$isImageBlock) { ?>  
                                                <?php if ($use_google_voice) { ?>
                                                    <div class="chat-audio"><img data-play="false" src="<?php echo $base_url;?>/img/btn_tts_play.svg"></div>
                                                <?php } ?>     
                                                <?php if($AI->display_copy_btn){?>                                           
                                                <span onclick="copyText(this)" class="copy-text" title="<?php echo $lang['copy_text1']; ?>"><i class="bi bi-clipboard"></i></span>
                                                <?php } ?>
                                            <?php } ?>                                            
                                        </div><!--chat-actions-->
                                    </div>
                                    <div class="message-text"><div class="chat-response"><?php echo $isImageBlock ? $content : removeCustomInput(stripslashes($content)); ?></div></div>
                                    <div class="date-chat"><img src="<?php echo $base_url;?>/img/icon-clock.svg"> <?php echo $datetime; ?></div>
                                </div>
                            </div>
                        </div>

                      <?php } ?>

                    <?php } ?>


                  <div id="overflow-chat">

                      <?php if (empty($_SESSION['history'][$AI_ID])) : ?>
                          <?php displayMessage('assistant', $AI->name, stripslashes($AI->display_welcome_message) ? stripslashes($AI->welcome_message) : null, null, date("d/m/Y, H:i:s"), $AI->image, $AI->display_avatar, $AI->use_google_voice, null); ?>
                      <?php else : ?>

                      <?php
                          $counter = 0;
                          foreach ($_SESSION['history'][$AI_ID] as $message) :
                            if ($message['role'] != "system") {
                              @$name = $message['role'] == 'assistant' ? $message['name'] : $lang['you'];
                              @$content = $message['role'] == 'assistant' ? $message['content'] : $message['content'];
                              @$extra_class = $counter > 1 ? 'conversation-thread-flow' : '';
                              displayMessage($message['role'], $AI->name, $message['content'], $message['dall_e_array'], $message['datetime'], $AI->image, $AI->display_avatar, $AI->use_google_voice, $extra_class);
                            }
                            $counter++;
                          
                          endforeach;
                      ?>

                      <?php endif; ?>

                  </div>
              </div>          
              <?php } ?>
              <?php if(isset($vip) && !$vip){?>

              <?php if ($embed_chat || ($isLogged || $_SESSION['message_count'] <= $config->free_number_chats)){ ?>
                
                <?php if(isset($userCredits)): ?>
                  <?php if(!$config->free_mode){?>
                  <span class="my-credits my-credits-mobile" style="display:none">
                    <?php echo $lang['my_credits']; ?>: <?php echo number_format($userCredits, 0, '.', ','); ?>
                  </span>
                  <?php } ?>
                <?php endif; ?>
        
              <div class="message-area-bottom">

                <!--start-widget--options--input-->
                <div class="col col-options-input">
                
                <?php if($AI->display_prompts_output || $AI->show_prompts_tone || $AI->display_prompts_writing) {?>
                <div class="btn-options-input"><div class="arrow-up"></div></div>
                <?php } ?>

                  <div style="<?php echo ($getPromptsOutputCount > 0 && $AI->display_prompts_output) ? 'display: block;' : 'display: none;'; ?>">
                    <div class="form-floating form-f-chat" id="display_chat_language_output">
                      <select class="form-select" id="selectLanguage">
                        <option value=""><?php echo $lang['label_default']; ?></option>
                        <?php foreach ($getPromptsOutput as $show_prompts_output) {?>
                        <option <?php if($AI->id_prompts_output_default == $show_prompts_output->id) echo "selected"; ?> value="<?php echo $show_prompts_output->value; ?>"><?php echo $show_prompts_output->name; ?></option>
                        <?php } ?>
                      </select> 
                      <label for="selectLanguage"><?php echo $lang['label_display_chat_language_output']; ?></label>
                    </div>
                  </div>

                  <div style="<?php echo ($getPromptsToneCount > 0 && $AI->display_prompts_tone) ? 'display: block;' : 'display: none;'; ?>">
                    <div class="form-floating form-f-chat" id="display_chat_tone">
                        <select class="form-select" id="selectTone">
                        <option value=""><?php echo $lang['label_default']; ?></option>
                        <?php foreach ($getPromptsTone as $show_prompts_tone) {?>
                        <option <?php if($AI->id_prompts_tone_default == $show_prompts_tone->id) echo "selected"; ?> value="<?php echo $show_prompts_tone->value; ?>"><?php echo $show_prompts_tone->name; ?></option>
                        <?php } ?>                      
                        </select>
                        <label for="selectTone"><?php echo $lang['label_display_chat_tone']; ?></label>
                    </div>
                  </div>

                  <div style="<?php echo ($getPromptsWritingCount > 0 && $AI->display_prompts_writing) ? 'display: block;' : 'display: none;'; ?>">
                    <div class="form-floating form-f-chat" id="display_chat_writing_style">
                      <select class="form-select" id="selectWritingStyle">
                        <option value=""><?php echo $lang['label_default']; ?></option>
                        <?php foreach ($getPromptsWriting as $show_prompts_writing) {?>
                        <option <?php if($AI->id_prompts_writing_default == $show_prompts_writing->id) echo "selected"; ?> value="<?php echo $show_prompts_writing->value; ?>"><?php echo $show_prompts_writing->name; ?></option>
                        <?php } ?>                      
                      </select>
                      <label for="selectWritingStyle"><?php echo $lang['label_display_chat_writing_style']; ?></label>
                    </div>
                  </div>

                </div>                
                <!--end-widget--options--input-->
                <?php } ?>
              

              <div class="chat-input">

                <?php 
                if(!$isLogged && $_SESSION['message_count'] > $config->free_number_chats && !$embed_chat){?>
                  <div class="col-12 chat-alert-mobile">
                    <div class="alert alert-warning mb-0">
                      <h5><?php echo $lang['create_account_to_continue_title']; ?></h5>
                      <p><?php echo $lang['create_account_to_continue_text']; ?></p>
                      <div class="d-flex">
                        <a class="nav-link btn btn-sign-up" href="<?php echo $base_url; ?>/sign-up"><i class="bi bi-box-arrow-in-right fs-5"></i> <?php echo $lang['sign_up']; ?></a>
                        <a class="nav-link btn btn-sign-in ms-3" href="<?php echo $base_url; ?>/sign-in"><i class="bi bi-person-circle fs-5"></i> <?php echo $lang['sign_in']; ?></a>
                      </div>
                    </div>
                  </div>
                <?php }else {?>

                <span class="character-typing">
                  <div><b class='wait'><?php echo $lang['wait']; ?></b> <span></span>  <b class='is_typing'><?php echo $lang['is_typing']; ?></b></div>
                </span>

              
                <textarea name="chat" id="chat" placeholder="<?php echo $lang['input_placeholder']; ?>" minlength="<?php echo $AI->chat_minlength; ?>" maxlength="<?php echo $AI->chat_maxlength; ?>"></textarea>
                <?php if($AI->display_mic && !$embed_chat){?>
                <img src="<?php echo $base_url; ?>/img/mic-start.svg" id="microphone-button">
                <?php } ?>
                <button class="submit btn-send-chat btn" tabindex="0"><span><?php echo $lang['button_send']; ?></span> <img src="<?php echo $base_url; ?>/img/icon-send.svg"></button>
                <button class="submit btn-cancel-chat btn" tabindex="0" style="display:none"><img src="<?php echo $base_url; ?>/img/btn_stop.svg"> <span class="stop-chat-label"><?php echo $lang['button_cancel']; ?></span></button>
                <?php } ?>
              </div>

              </div>    
              <?php } //vip ?> 

            </div>
          </div>          
        </div>         

        </div>
      </div>
    </div> 
  </section>

  <style type="text/css">
    .chat-response{
      font-size: <?php echo $config->chat_font_size; ?>;
    }
  </style>

<?php 
require_once("inc/footer.php");
?>