<?php
// Initializing variables and requiring necessary files
$header_min = true;
$use_bootstrap_icons = true;
$embed_chat = false;
$share = true;
require_once("inc/includes.php");
$getByThread = $messages->getByThread($_REQUEST['slug']);
$lastThread = $messages->getByThread($_REQUEST['slug'])->Fetch();

if (!$getByThread->rowCount()) {
    header("location:/404");
    exit();
}

$getCustomer = $customers->getCustomer($lastThread->id_customer);
$getPrompt = $prompts->get($lastThread->id_prompt);

define('META_TITLE', $lang['share_chat_conversation_between']." ".$getPrompt->name." ".$lang['share_chat_and']." ".$getCustomer->name);
define('META_DESCRIPTION', $lang['share_chat_conversation_between']." ".$getPrompt->name." ".$lang['share_chat_and']." ".$getCustomer->name);

// Include chat session script
require_once("inc/header.php");
require_once("modules/customer/chat-session.php");
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="chat-frame share-chat">
                    <div class="share-chat-header">
                        <div class="share-chat-header-info">
                            <h2><?php echo $lang['share_chat_conversation_between']; ?> <?php echo $getPrompt->name; ?> <?php echo $lang['share_chat_and']; ?> <?php echo $getCustomer->name; ?></h2>
                            <h5><i class="bi bi-calendar"></i> <?php echo formatDate($getByThread->fetch()->created_at, false); ?></h5>
                        </div>

                        <div class="share-chat-header-buttons">
                            <div class="share-chat-header-info-download">
                                <a href="<?php echo $base_url."/download-chat/".$getPrompt->slug."/".$lastThread->id_thread."?format=pdf&share=true"; ?>">
                                  <div class="chat-action-buttons">
                                     <i class="bi bi-filetype-pdf"></i>
                                  </div>                            
                                </a>
                            </div>

                            <div class="share-chat-header-info-download">
                                <a href="<?php echo $base_url."/download-chat/".$getPrompt->slug."/".$lastThread->id_thread."?format=docx&share=true"; ?>">
                                  <div class="chat-action-buttons">
                                     <i class="bi bi-filetype-docx"></i>
                                  </div>                            
                                </a>
                            </div>

                            <div class="share-chat-header-info-download">
                                <a href="<?php echo $base_url."/download-chat/".$getPrompt->slug."/".$lastThread->id_thread."?format=txt&share=true"; ?>">
                                  <div class="chat-action-buttons">
                                     <i class="bi bi-filetype-txt"></i>
                                  </div>                            
                                </a>
                            </div>                            
                        </div>

                    </div>

                    <div class="share-chat-thread">
                        <?php 
                        foreach ($getByThread as $showByThread) {
                         if($showByThread->role != "system"){
                         $isImageBlock = false;

                         $json_array = json_decode($showByThread->dall_e_array, true);
                         if (json_last_error() === JSON_ERROR_NONE && isset($json_array['data'])) {
                             $content = '<p><strong class="ia-image-prompt-label">'.$showByThread->content.'</strong></p><div class="wrapper-image-ia image_ia_' . time() . '">';
                             foreach ($json_array['data'] as $item) {
                                 if (isset($item['url'])) {
                                     $imageName = $item['url'];
                                     $content .= '<div class="image-ia"><img onerror="this.src=\'' . $base_url . '/img/no-image.svg\'" src="' . $base_url . '/public_uploads/dalle/' . $imageName . '"></div>';
                                     $isImageBlock = true;
                                 }
                             }
                             $content .= '</div>';
                         } else {
                             $content = removeCustomInput($showByThread->content);
                         }
                         ?>
                                <div class="conversation-thread <?php echo $showByThread->role == 'assistant' ? 'thread-ai' : 'thread-user'; ?>">
                                    <?php if ($getPrompt->display_avatar && $showByThread->role == 'assistant') { ?>
                                        <div class="user-image"><img onerror="this.src='<?php echo $base_url;?>/img/no-image.svg'" src="<?php echo $base_url;?>/public_uploads/<?php echo $getPrompt->image; ?>" alt="<?php echo $getPrompt->name; ?>" title="<?php echo $getPrompt->name; ?>"></div>
                                    <?php } ?>
                                    <div class="message-container">
                                        <div class="message-info">
                                            <div class="wrapper-chat-header">
                                                <div class="user-name"><h5><?php echo $showByThread->role == 'assistant' ? $getPrompt->name : $lang['you']; ?></h5></div>
                                                <div class="chat-actions">
                                                    <?php if (!$isImageBlock) { ?>  
                                                        <?php if ($getPrompt->use_google_voice) { ?>
                                                            <div class="chat-audio"><img data-play="false" src="<?php echo $base_url;?>/img/btn_tts_play.svg"></div>
                                                        <?php } ?>     
                                                        <?php if($getPrompt->display_copy_btn){?>                                           
                                                        <span onclick="copyText(this)" class="copy-text" title="<?php echo $lang['copy_text1']; ?>"><i class="bi bi-clipboard"></i></span>
                                                        <?php } ?>
                                                    <?php } ?>                                            
                                                </div><!--chat-actions-->
                                            </div>
                                            <div class="message-text"><div class="chat-response"><?php echo $content; ?></div></div>
                                            <div class="date-chat"><img src="<?php echo $base_url;?>/img/icon-clock.svg"> <?php echo $showByThread->created_at; ?></div>
                                        </div>
                                    </div>
                                </div>
                        <?php } ?>
                        <?php } ?>                        
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>  

<?php 
require_once("inc/footer.php");
?>


<?php 
require_once("inc/footer.php");
?>