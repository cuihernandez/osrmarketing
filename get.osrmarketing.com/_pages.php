<?php 
$bg_white = true;
$header_min = true;
$loadAI = false;
$use_bootstrap_icons = true;
require_once("inc/includes.php");
if(isset($_REQUEST['slug']) && $_REQUEST['slug']){
  $slug = addslashes($_REQUEST['slug']);
  $get = $pages->getBySlug($slug);
  if(!$get){
    redirect($base_url.'/404', "", 'error');
  }
}else{
  redirect($base_url.'/404', "", 'error');
}
define('META_TITLE', $get->meta_title);
define('META_DESCRIPTION', $get->meta_description);
require_once("inc/header.php");
?>

<section id="inner-page">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-12"><h1 class="mb-0"><?php echo $get->name; ?></h1></div>
    </div>
  </div>  
</section>

<section class="section-spacing">
  <div class="container">
    <div class="col-12">
        <?php echo stripslashes($get->content); ?>

        <?php 
        if ($slug == "msp-sales-marketing-operations") {
        ?>
        <!-- Start of the 4-column layout for msp-sales-marketing-operations page -->
        <div class="container mt-5" style="background-color: #8CBF40; border-radius: 15px; height: 600px; width: 100%;">
            <div class="row h-100">
                <div class="col-md-12 border-center">
                    <iframe
                style="border: none;"
                srcdoc="<body><script src='https://cdn.botpress.cloud/webchat/v0/inject.js'></script>
                <script>
                    window.botpressWebChat.init({
                        'composerPlaceholder': 'Assists with Account Manager Processes',
                        'botConversationDescription': 'Account Manager Operations Bot',
                        'botName': 'Allison Bot',
                        'botId': 'd68548ad-796b-4559-b377-603c110982da',
                        'hostUrl': 'https://cdn.botpress.cloud/webchat/v0',
                        'messagingUrl': 'https://messaging.botpress.cloud',
                        'clientId': 'd68548ad-796b-4559-b377-603c110982da',
                        'enableConversationDeletion': true,
                        'showPoweredBy': false,
                        'avatarUrl': 'https://www.osrmanage.com/wp-content/uploads/2021/11/i-statistic.png',
                        'className': 'webchatIframe',
                        'containerWidth': '100%25',
                        'layoutWidth': '100%25',
                        'hideWidget': false,
                        'showCloseButton': false,
                        'disableAnimations': true,
                        'closeOnEscape': false,
                        'showConversationsButton': true,
                        'enableTranscriptDownload': true,
                        'stylesheet':'https://webchat-styler-css.botpress.app/prod/code/3fcd3e4e-d5bc-4bf5-8699-14b621b3ada2/v31782/style.css'
                    });
                    window.botpressWebChat.onEvent(function () { window.botpressWebChat.sendEvent({ type: 'show' }) }, ['LIFECYCLE.LOADED']);
                </script></body>"
                width="100%"
                height="100%"
            ></iframe>
                </div>
                
            </div>
        </div>
        <!-- End of the 4-column layout -->
        <?php
        } // end of conditional check
        ?>

    </div>  
  </div>
</section>


<?php
require_once("inc/footer.php");
?>