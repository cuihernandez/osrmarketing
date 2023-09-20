<?php 
$header_min = true;
$no_footer = true;
$use_bootstrap_icons = true;
require_once("customer-restrict.php");
require_once("../../inc/includes.php");

if ($config->customer_confirm_email && !empty($getCustomer->confirm_email_token)) {

    if (isset($_GET['confirm']) && $_GET['confirm']) {
        unset($_SESSION['email_error']);

        if($getCustomer->confirm_email_token == $_GET['confirm']){
          if($customers->confirmEmailCustomer($getCustomer->id)){
            redirect($base_url.'/panel', $lang['login_success_message'], 'success');
          }else{
            $_SESSION['email_error'] = $lang['error_update_record'];
          }
        }else{
          $_SESSION['email_error'] = $lang['confirm_email_unable_verify'];
        }

    }
    if (isset($_GET['resend']) && $_GET['resend'] == 'true') {
        $canSendEmail = true;

        if (isset($_SESSION['last_email_sent_time'])) {
            $timeDifference = time() - $_SESSION['last_email_sent_time'];
            if ($timeDifference < 30) {
                $canSendEmail = false;
                $_SESSION['email_error'] = $lang['confirm_email_resend_message'];
            } else {
                unset($_SESSION['email_error']);
            }
        }


        if ($canSendEmail) {
            $smtp_fields = array(
                'subject' => $config->customer_confirm_email_subject,
                'email' => $getCustomer->email,
                'type' => 'confirm_email_customer',
                'email_customer_link' => $getCustomer->confirm_email_token,
                'recipient_name' => $getCustomer->name
            );
            $emailSent = sendEmail($smtp_fields);

            // Armazene o tempo atual na sessão
            $_SESSION['last_email_sent_time'] = time();
        }
    }

}


$getCustomerChat = $messages->getPromptByIdUser($_SESSION['id_customer']);
$getCustomerChatCount = $getCustomerChat->rowCount();

if(isset($_REQUEST['action']) && $_REQUEST['action'] == "get-chats"){
  $getChatBySlug = $prompts->getBySlug($_REQUEST['slug']);
  if($getChatBySlug){
    $getThreads = $messages->getThreadByIdUserAndPrompt((int) $_SESSION['id_customer'],(int) $getChatBySlug->id);


    //var_dump($getThreads);
    if($getThreads){
      $countThreads = $getThreads->rowCount();
    }else{
      $countThreads = 0;
    }
  }else{
    header("Location:".$base_url."/panel");
    die();
  }
}


define('META_TITLE', $lang['my_chats_title']);
define('META_DESCRIPTION', $lang['my_chats_title']);
require_once("../../inc/header.php");
?>

<section id="inner-page">
  <div class="container">
    <div class="row">
      <div class="col"><h3><?php echo $lang['welcome_title']." ".$_SESSION['name_customer_name']; ?></h3></div>
    </div>
  </div>  
</section>

<section id="panel-area">
  <div class="container">
    <div class="row">

    <?php if ($config->customer_confirm_email && !empty($getCustomer->confirm_email_token)) {?>
      <div class="col">
          <div class="white-card content-panel text-center">
              <h3><i class="bi bi-envelope-exclamation-fill fs-3"></i> 
                  <?php 
                      echo isset($lang['confirm_email_title']) ? $lang['confirm_email_title'] : ''; 
                  ?>
              </h3>
              <p class="alert alert-warning">
                  <?php 
                      echo isset($lang['confirm_email_message']) ? $lang['confirm_email_message'] : ''; 
                  ?>
              </p>
              <?php 
              if (isset($_SESSION['email_error'])) {
                  echo "<p class='alert alert-danger'>".$_SESSION['email_error']."</p>";
              }
              ?>
          </div>
          <div class='text-center'>
              <a class="btn btn-primary" href="<?php echo $base_url."/logout"; ?>">
                  <i class="bi bi-box-arrow-left"></i> 
                  <?php echo isset($lang['menu_logout']) ? $lang['menu_logout'] : 'Logout'; ?>
              </a>
              <a class="btn btn-success" href="<?php echo $base_url."/panel?resend=true"; ?>">
                  <i class="bi bi-send"></i> 
                  <?php echo isset($lang['confirm_email_resend_button']) ? $lang['confirm_email_resend_button'] : 'Resend'; ?>
              </a>
          </div>
      </div>
      <?php }else{ ?>

      <div class="col-12 col-sm-12 col-md-3 col-lg-3">
        <?php require_once("_customer-sidebar.php");?>      
      </div>
      <div class="col">
        <div class="white-card content-panel">

        <?php
            if (!empty($_SESSION['action'])) {
                $alertClass = $_SESSION['action'] === 'error' ? 'danger' : $_SESSION['action'];
                echo "<div class='alert alert-$alertClass'>{$_SESSION['action_message']}</div>";
            }
        ?>

          <?php if(isset($_REQUEST['action']) && $_REQUEST['action'] == "get-chats"){?>
          
          <div class="row align-items-center">
            <div class="col-md-6 col-lg-6 col-12">
              <div class="wrapper-ai-chat-top">
                <div class="ai-chat-top-image"><img src="<?php echo $base_url."/public_uploads/".$getChatBySlug->image; ?>" alt="<?php echo $getChatBySlug->name; ?>" title="<?php echo $getChatBySlug->name; ?>" onerror="this.src='http://teste.local/img/no-image.svg'"></div>
                <div class="ai-chat-top-info">
                  <div class="ai-chat-top-name"><h4><?php echo $getChatBySlug->name; ?> <span class="online-bullet"></span></h4></div>
                  <div class="ai-chat-top-job"><?php echo $getChatBySlug->expert; ?></div>
                </div>
              </div>              
            </div>
            <div class="col-md-6 col-lg-6 col-12">
              <a class="btn btn-danger d-block float-md-end mb-2 mb-md-0" href="<?php echo $base_url."/panel";?>"><i class="bi bi-arrow-left"></i> <?php echo $lang['btn_customer_back']; ?></a>
              <a class="btn btn-success d-block float-md-end me-md-3" href="<?php echo $base_url."/new-chat/".$getChatBySlug->slug;?>"><i class="bi bi-chat-dots"></i> <?php echo $lang['btn_customer_new_chat']; ?></a>
            </div>
          </div>


          <?php if($countThreads > 0){?>
          <div class="alert alert-secondary mt-3 d-flex flex-column justify-content-center">
            <div><?php echo $lang['customer_total_chat_part1']; ?> <strong class="color-blue"><?php echo $countThreads; ?></strong> <?php echo $lang['customer_total_chat_part2']; ?></div>
          </div>            
          <?php } ?>      

          <div class="row">
            <div class="col">
              <ul class="list-group">
                <?php 
                  $n = 1;
                  if($getThreads){
                  foreach ($getThreads as $showThreads){
                  $thread_link = $base_url."/chat/".$showThreads->slug."?chat=".$showThreads->id_thread;
                ?>
                <li class="list-group-item justify-content-between align-items-center">
                  <div class="ms-2 me-auto">
                    <strong class="color-blue"><i class="bi bi-chat-left"></i> <?php echo $lang['chat_label_list']; ?> <?php echo $countThreads - $n + 1;?></strong><br>
                    <b><?php echo isset($lang['last_message']) && $lang['last_message'] !== '' ? $lang['last_message'] : ''; ?></b> <?php echo truncateText(removeCustomInput($showThreads->last_message_content),150); ?><br>
                  </div>
                  <div class="mt-2">
                    <a class="btn btn-success btn-sm" href="<?php echo $thread_link; ?>"><i class="bi bi-chat-dots"></i> <?php echo $lang['btn_customer_chat_now']; ?></a>
                    <span class="dropdown">
                      <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $lang['btn_options']; ?>
                      </button>
                      <ul class="dropdown-menu">
                        <li>
                          <a class="dropdown-item" href="<?php echo $base_url; ?>/download-chat/<?php echo $showThreads->slug; ?>/<?php echo $showThreads->id_thread; ?>?format=txt">
                          <i class="bi bi-filetype-txt"></i> 
                          <?php echo $lang['button_download_chat']; ?>
                        </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="<?php echo $base_url; ?>/download-chat/<?php echo $showThreads->slug; ?>/<?php echo $showThreads->id_thread; ?>?format=pdf">
                          <i class="bi bi-filetype-pdf"></i> 
                          <?php echo $lang['button_download_chat_pdf']; ?>
                        </a>
                        </li>
                        <li>
                          <a class="dropdown-item" href="<?php echo $base_url; ?>/download-chat/<?php echo $showThreads->slug; ?>/<?php echo $showThreads->id_thread; ?>?format=docx">
                          <i class="bi bi-filetype-docx"></i> 
                          <?php echo $lang['button_download_chat_docx']; ?>
                          </a>
                        </li>
                                 
                      </ul>
                       <button onclick="shareChat('<?php echo $base_url."/share/".$showThreads->id_thread; ?>')" class="btn btn-sm btn-outline-primary" alt="<?php echo $lang['share']; ?>" title="<?php echo $lang['share']; ?>"><i class="bi bi bi-share"></i></button>
                    </span>                       
                  </div>
                </li>
                <?php $n++;} ?>
              <?php } ?>
              </ul>              
            </div>
          </div>


          <?php }else{ ?>
          
            <div class="row mt-2">
            <?php if($getCustomerChatCount == 0){ ?>
              <div class="col">
                <div class="alert alert-secondary mt-3 align-items-center text-center">
                  <img src="<?php echo $base_url."/img/no-chat.svg";?>">
                  <div class="mt-2 mb-1"><p class="mb-1"><?php echo $lang['no_chats_message']; ?></p></div>
                  <div class="mt-0"><a class="btn btn-success" href="<?php echo $base_url."/ai-team";?>"><i class="bi bi-chat-dots"></i> <?php echo $lang['no_chats_message_call_action']; ?></a></div>
                </div>
              </div>
            <?php } else{ ?>
              <h4 class="mb-3"><?php echo $lang['my_chats_title']; ?></h4>
            <?php } ?>
            
            <?php foreach ($getCustomerChat as $showCustomerChat) {?>
                <div class="col-md-12">
                  <div class="panel-ai-chat">

                    <div class="wrapper-ai-chat-top">
                      <div class="ai-chat-top-image"><img src="<?php echo $base_url."/public_uploads/".$showCustomerChat->image; ?>" alt="<?php echo $showCustomerChat->name; ?>" title="<?php echo $showCustomerChat->name; ?>" onerror="this.src='<?php echo $base_url;?>/img/no-image.svg'"></div>
                      <div class="ai-chat-top-info">
                        <div class="ai-chat-top-name"><h4><?php echo $showCustomerChat->name; ?> <span class="online-bullet"></span></h4></div>
                        <div class="ai-chat-top-job"><?php echo $showCustomerChat->expert; ?></div>
                        <div class="ai-chat-created"><img src="<?php echo $base_url;?>/img/icon-clock.svg"><span> <?php echo formatDate($showCustomerChat->first_created_at, false); ?></span></div>
                      </div>
                    </div>

                    <div class="wrapper-ai-number-chats-messages">
                      <img src="<?php echo $base_url;?>/img/chat.png">
                      <span><?php echo $showCustomerChat->num_unique_threads; ?> <?php echo $lang['chats_label']; ?></span>
                    </div>

                    <div class="wrapper-ai-number-chats-messages">
                      <img src="<?php echo $base_url;?>/img/message.png">
                      <span><?php echo $showCustomerChat->num_messages-1; ?> <?php echo $lang['messages_label']; ?></span>
                    </div>                  

                    <div class="wrapper-ai-action-messages text-center">
                      <a href="<?php echo $base_url."/panel/view-chats/".$showCustomerChat->slug; ?>" class="btn btn-secondary"><i class="bi bi-plus-circle"></i> <?php echo $lang['btn_view_chats']; ?></a>
                    </div>
                
                  </div>
                </div>
            <?php } ?>
            </div>

          <?php } ?>

        </div>
      </div>
    <?php } ?>
    </div>
  </div>
</section>

<?php 
require_once("../../inc/footer.php");
?>