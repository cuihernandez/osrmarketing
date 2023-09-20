<?php
$module_name = "settings";
$use_save_absolute = true;
$use_codemirror = true;
require_once(__DIR__."/../../inc/restrict.php");
require_once(__DIR__."/../../inc/includes.php");
require_once(__DIR__."/../../inc/header.php");
require_once(__DIR__."/../../helpers/render-header-module.php");
renderLanguageSection("<i class='bi bi-gear fs-3'></i> Settings", $module_name, "");
require_once(__DIR__."/../../helpers/message-session.php");
$get = $settings->get(1);
?>

  <div class="modal modal-lg fade" id="modalTestSMTP" tabindex="-1" aria-labelledby="modalTestSMTPLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalTestSMTPLabel">Test SMTP sending</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="smtpTestForm">
            <div class="mb-3">
              <label for="subject" class="form-label">Subject</label>
              <input type="text" class="form-control" id="subject" name="subject" placeholder="Inform a subject" value="Subject Example - Email Test">
            </div>
            <div class="mb-3">
              <label for="recipient_name" class="form-label">Recipient's Name</label>
              <input type="text" class="form-control" id="recipient_name" name="recipient_name" placeholder="Recipient's Name" value="Aigency">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Recipient's Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?php echo isset($_SESSION['admin_email']) ? $_SESSION['admin_email'] : ''; ?>">
            </div>
            <div class="mb-3">
              <label for="content" class="form-label">Content</label>
              <textarea class="form-control" id="content" name="content" rows="3" placeholder="Enter the content">Hello, this is an email test!</textarea>
            </div>
          </form>

          <div class="alert alert-secondary" role="alert" id="smtp_test_return">
            Click and send e-mail, and then see the response of the request here, check the recipient's email afterwards to verify if the email has arrived.
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-success" id="btn-test-smtp-email" onclick="submitFormTestSMTP()">Send e-mail</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>

      </div>

    </div>
  </div>

  <div>
    <form action="/admin/settings/action" method="post" novalidate enctype="multipart/form-data" id="form">

      <div id="spiner-loading-settings">
          <div id="loading-spinner" class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>        
      </div>
        
    <div class="row" id="tab-content" style="display: none">
      <div class="col-lg-3">
        <nav class="custom-nav-settings">
          <div class="nav nav-tabs flex-column" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-api-key-tab" data-bs-toggle="tab" data-bs-target="#nav-api-key" type="button" role="tab"><i class="bi bi-gear"></i> Api Keys</button>
            <button class="nav-link" id="nav-chat-options-tab" data-bs-toggle="tab" data-bs-target="#nav-chat-options" type="button" role="tab" aria-selected="false"><i class="bi bi-chat"></i> Chat options</button>
            <button class="nav-link" id="nav-header-tab" data-bs-toggle="tab" data-bs-target="#nav-header" type="button" role="tab" aria-selected="false"><i class="bi bi-moon-stars"></i> Header options</button>
            <button class="nav-link" id="nav-home-page-tab" data-bs-toggle="tab" data-bs-target="#nav-home-page" type="button" role="tab" aria-selected="false"><i class="bi bi-house"></i> Home page</button>
            <button class="nav-link" id="nav-email-configs-tab" data-bs-toggle="tab" data-bs-target="#nav-email-configs" type="button" role="tab" aria-selected="false"><i class="bi bi-envelope"></i> E-mail configs</button>
            <button class="nav-link" id="nav-home-maintenance-mode" data-bs-toggle="tab" data-bs-target="#nav-maintenance-mode" type="button" role="tab" aria-selected="false"><i class="bi bi-tools"></i> Maintenance mode</button>
            <button class="nav-link" id="nav-google-analytics-tab" data-bs-toggle="tab" data-bs-target="#nav-google-analytics" type="button" role="tab" aria-selected="false"><i class="bi bi-bar-chart"></i> Google Analytics</button>
            <button class="nav-link" id="nav-dalle-tab" data-bs-toggle="tab" data-bs-target="#nav-dalle" type="button" role="tab" aria-selected="false"><i class="bi bi-card-image"></i> DALL-E</button>
            <button class="nav-link" id="nav-bank-info-tab" data-bs-toggle="tab" data-bs-target="#nav-bank-info" type="button" role="tab" aria-selected="false"><i class="bi bi-bank"></i> Bank deposit info</button>
            <button class="nav-link" id="nav-custom-code-js-tab" data-bs-toggle="tab" data-bs-target="#nav-custom-code-js" type="button" role="tab" aria-selected="false"><i class="bi bi-filetype-js"></i> Custom code JS</button>
            <button class="nav-link" id="nav-custom-code-css-tab" data-bs-toggle="tab" data-bs-target="#nav-custom-code-css" type="button" role="tab" aria-selected="false"><i class="bi bi-filetype-css"></i> Custom code CSS</button>
            <button class="nav-link" id="nav-blog-sidebar-tab" data-bs-toggle="tab" data-bs-target="#nav-blog-sidebar" type="button" role="tab" aria-selected="false"><i class="bi bi-bookmark"></i> Blog</button>
            <button class="nav-link" id="nav-admin-login-tab" data-bs-toggle="tab" data-bs-target="#nav-admin-login" type="button" role="tab" aria-selected="false"><i class="bi bi-shield-check"></i> Admin login security</button>
            <button class="nav-link" id="nav-cache-tab" data-bs-toggle="tab" data-bs-target="#nav-cache" type="button" role="tab" aria-selected="false"><i class="bi bi-stars"></i> Cache</button>
            <button class="nav-link" id="nav-vip-tab" data-bs-toggle="tab" data-bs-target="#nav-vip" type="button" role="tab" aria-selected="false"><i class="bi bi-star-fill"></i> Vip</button>
            <button class="nav-link" id="nav-customer-tab" data-bs-toggle="tab" data-bs-target="#nav-customer" type="button" role="tab" aria-selected="false"><i class="bi bi-people"></i> Customer</button>
          </div>
        </nav>        
      </div>

      <div class="col-lg-9">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="nav-api-key" role="tabpanel" aria-labelledby="nav-api-key-tab" tabindex="0">
       
            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gear"></i> Open AI</h5></legend>
              <div class="row align-middle">

                <?php if($config->demo_mode){?>
                  <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?>
                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="openai_api_key" type="password" class="form-control" id="floatingPassword" placeholder="OpenAi Api Key" value="<?php echo $get->openai_api_key; ?>">
                      <label for="floatingOpenAIApiKey">OpenAi Api Key</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>                
                <?php } ?>

                <a href="https://platform.openai.com/account/api-keys" target="_blank">https://platform.openai.com/account/api-keys</a>
              </div>
            </fieldset> 

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gear"></i> Google Cloud Translation API</h5></legend>
              <div class="row align-middle">

                <?php if($config->demo_mode){?>
                  <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?>
                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="google_cloud_translation_api_key" type="password" class="form-control" id="floatingGoogleTranslateKey" placeholder="Cloud Translation API Key" value="<?php echo $get->google_cloud_translation_api_key; ?>">
                      <label for="floatingGoogleTranslateKey">Cloud Translation API Key</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>                
                <?php } ?>
              </div>
            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gear"></i> Google Cloud Text to Speech API</h5></legend>
              <div class="row align-middle">
                <?php if($config->demo_mode){?>
                  <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?>
                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="google_cloud_text_to_speech_api_key" type="password" class="form-control" id="floatingGoogleTranslateKey" placeholder="Google Text to Speech API Key" value="<?php echo $get->google_cloud_text_to_speech_api_key; ?>">
                      <label for="floatingGoogleTranslateKey">Google Text to Speech API Key</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>                
                <?php } ?>
              </div>
            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-stripe"></i> Stripe (Payment method)</h5></legend>
              <div class="row align-middle">

                <?php if($config->demo_mode){?>
                  <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?>                
                  <div class="col-md-12">
                    <div class="form-check form-switch mb-3 custom-switch">
                      <input class="form-check-input" type="checkbox" id="floatingDisplayStripeActive" <?php if ($get->stripe_payment_active == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayStripeActive', 'hiddenDisplayStripeActive')">
                      <input type="hidden" name="stripe_payment_active" id="hiddenDisplayStripeActive" value="<?php echo $get->stripe_payment_active; ?>">
                      <label class="form-check-label" for="floatingDisplayStripeActive">Use Stripe payment method</label>
                    </div>        
                  </div>

                  <div class="col-md-12">
                    <div class="form-check form-switch mb-3 custom-switch">
                      <input class="form-check-input" type="checkbox" id="floatingDisplayStripeTestMode" <?php if ($get->stripe_test_mode == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayStripeTestMode', 'hiddenDisplayStripeTestMode')">
                      <input type="hidden" name="stripe_test_mode" id="hiddenDisplayStripeTestMode" value="<?php echo $get->stripe_test_mode; ?>">
                      <label class="form-check-label" for="floatingDisplayStripeTestMode">Enable stripe test mode (Sandbox)</label>
                    </div>        
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="stripe_api_key_test" type="password" class="form-control" id="floatingStripeApiKeyTest" placeholder="Stripe Api Key (Test)" value="<?php echo $get->stripe_api_key_test; ?>">
                      <label for="floatingStripeApiKeyTest">Stripe Api Key (Test)</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="stripe_api_key_production" type="password" class="form-control" id="floatingStripeApiKeyProduction" placeholder="Stripe Api Key (Production)" value="<?php echo $get->stripe_api_key_production; ?>">
                      <label for="floatingStripeApiKeyProduction">Stripe Api Secret Key (Production)</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="stripe_webhook_secret_test" type="password" class="form-control" id="floatingStripeWebhookTest" placeholder="Stripe Webhook Key" value="<?php echo $get->stripe_webhook_secret_test; ?>">
                      <label for="floatingStripeWebhookTest">Stripe Webhook Key (Test)</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="stripe_webhook_secret_production" type="password" class="form-control" id="floatingStripeWebhookProduction" placeholder="Stripe Webhook Key" value="<?php echo $get->stripe_webhook_secret_production; ?>">
                      <label for="floatingStripeWebhookProduction">Stripe Webhook Key (Production)</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>
                <?php } ?>

                <a href="https://dashboard.stripe.com/apikeys" target="_blank">https://dashboard.stripe.com/apikeys</a>
                <a href="https://dashboard.stripe.com/test/webhooks" target="_blank">https://dashboard.stripe.com/test/webhooks</a>
                <a href="https://stripe.com/docs/testing" target="_blank">https://stripe.com/docs/testing</a>
              </div>
            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-paypal"></i> PayPal (Payment method)</h5></legend>
              <div class="row align-middle">

                <?php if($config->demo_mode){?>
                  <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?>                
                  <div class="col-md-12">
                    <div class="form-check form-switch mb-3 custom-switch">
                      <input class="form-check-input" type="checkbox" id="floatingDisplayPayPalActive" <?php if ($get->paypal_payment_active == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayPayPalActive', 'hiddenDisplayPayPalActive')">
                      <input type="hidden" name="paypal_payment_active" id="hiddenDisplayPayPalActive" value="<?php echo $get->paypal_payment_active; ?>">
                      <label class="form-check-label" for="floatingDisplayPayPalActive">Use PayPal payment method</label>
                    </div>        
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="paypal_clientid_production" type="password" class="form-control" id="floatingPayPalClientIdProduction" placeholder="PayPal Client ID (Production)" value="<?php echo $get->paypal_clientid_production; ?>">
                      <label for="floatingPayPalClientIdProduction">PayPal Client ID (Production)</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="paypal_secret_production" type="password" class="form-control" id="floatingPayPalSecretKeyProduction" placeholder="PayPal Secret Key (Production)" value="<?php echo $get->paypal_secret_production; ?>">
                      <label for="floatingPayPalSecretKeyProduction">PayPal Secret Key (Production)</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>

                  <div class="alert alert-light">
                    <h6>Test mode (sandbox)</h6>

                    <div class="col-md-12">
                      <div class="form-check form-switch mb-3 custom-switch">
                        <input class="form-check-input" type="checkbox" id="floatingDisplayPayPalTestMode" <?php if ($get->paypal_test_mode == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayPayPalTestMode', 'hiddenDisplayPayPalTestMode')">
                        <input type="hidden" name="paypal_test_mode" id="hiddenDisplayPayPalTestMode" value="<?php echo $get->paypal_test_mode; ?>">
                        <label class="form-check-label" for="floatingDisplayPayPalTestMode">Enable PayPal test mode (Sandbox)</label>
                      </div>        
                    </div>

                    <div class="col-md-12">
                      <div class="form-floating mb-3 position-relative" data-toggle-password>
                        <input name="paypal_clientid_test" type="password" class="form-control" id="floatingPayPalClientIdTest" placeholder="PayPal Client ID (Test)" value="<?php echo $get->paypal_clientid_test; ?>">
                        <label for="floatingPayPalClientIdTest">PayPal Client ID (Test)</label>
                        <i class="bi bi-eye-slash toggle-password"></i>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-floating mb-3 position-relative" data-toggle-password>
                        <input name="paypal_secret_test" type="password" class="form-control" id="floatingPayPalSecretKeyTest" placeholder="PayPal Secret Key PayPal Secret Key (Test)" value="<?php echo $get->paypal_secret_test; ?>">
                        <label for="floatingPayPalSecretKeyTest">PayPal Secret Key (Test)</label>
                        <i class="bi bi-eye-slash toggle-password"></i>
                      </div>
                    </div>

                  </div>
                                
                <?php } ?>

                <a href="https://developer.paypal.com/dashboard/" target="_blank">https://developer.paypal.com/dashboard/</a>
              </div>
            </fieldset>


            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-shield-check"></i> Google Recaptcha</h5></legend>
              <div class="row align-middle">


                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayUseRecaptcha" <?php if ($get->use_recaptcha == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayUseRecaptcha', 'hiddenDisplayUseRecaptcha')">
                    <input type="hidden" name="use_recaptcha" id="hiddenDisplayUseRecaptcha" value="<?php echo $get->use_recaptcha; ?>">
                    <label class="form-check-label" for="floatingDisplayUseRecaptcha">Use recaptcha</label>
                  </div>        
                </div>

                <?php if($config->demo_mode){?>
                  <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the key is not shown in demo mode.</div></div>
                <?php }else{ ?> 
                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="recaptcha_public_key" type="password" class="form-control" id="floatingRecaptchaPublicKey" placeholder="Recaptcha Public Key" value="<?php echo $get->recaptcha_public_key; ?>">
                      <label for="floatingRecaptchaPublicKey">Recaptcha Public Key</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-floating mb-3 position-relative" data-toggle-password>
                      <input name="recaptcha_secret_key" type="password" class="form-control" id="floatingRecaptchaSecretKey" placeholder="Recaptcha Secret Key" value="<?php echo $get->recaptcha_secret_key; ?>">
                      <label for="floatingRecaptchaSecretKey">Recaptcha Secret Key</label>
                      <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  </div>
                <?php } ?>

                <a href="https://www.google.com/recaptcha/admin/" target="_blank">https://www.google.com/recaptcha/admin/</a>
              </div>
            </fieldset>                       

          </div><!--nav-api-key-->

          <div class="tab-pane fade" id="nav-chat-options" role="tabpanel" aria-labelledby="nav-chat-options-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-chat"></i> Chat Options</h5></legend>

              <div class="row">

                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <input name="chat_font_size" type="text" class="form-control" id="floatingChatFontSize" placeholder="Chat Font Size" value="<?php echo $get->chat_font_size; ?>">
                    <label for="floatingChatFontSize">Chat Font Size</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <input name="max_tokens_gpt" type="number" class="form-control" id="floatingMaxTokensGPT" placeholder="Max tokens GPT Model" value="<?php echo $get->max_tokens_gpt; ?>">
                    <label for="floatingMaxTokensGPT">Max tokens GPT Model</label>
                  </div>
                </div> 

                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <input name="max_tokens_davinci" type="number" class="form-control" id="floatingMaxTokensDavinci" placeholder="Max tokens Davinci Model" value="<?php echo $get->max_tokens_davinci; ?>">
                    <label for="floatingMaxTokensDavinci">Max tokens Davinci Model</label>
                  </div>
                </div>

              <div class="col-md-4">
                <div class="form-check form-switch mb-3 custom-switch">
                  <input class="form-check-input" type="checkbox" id="floatingChatFullWidth" <?php if ($get->chat_full_width == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingChatFullWidth', 'hiddenChatFullWidth')">
                  <input type="hidden" name="chat_full_width" id="hiddenChatFullWidth" value="<?php echo $get->chat_full_width; ?>">
                  <label class="form-check-label" for="floatingChatFullWidth">Chat full width</label>
                </div>
              </div>          

              </div>

            </fieldset>


            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gift"></i> Creation account credit bonus.</h5></legend>
              <div class="row align-middle">

                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input name="credit_account_bonus" type="number" class="form-control" id="floatingBonusCreditsAccount" placeholder="Creation account credit bonus." value="<?php echo $get->credit_account_bonus; ?>">
                    <label for="floatingBonusCreditsAccount">Creation account credit bonus.</label>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input name="free_number_chats" type="number" class="form-control" id="floatingFreeNumberChats" placeholder="Number of free chats before login." value="<?php echo $get->free_number_chats; ?>">
                    <label for="floatingFreeNumberChats">Number of free chats before login</label>
                  </div>
                </div>                                  

              </div>
            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-gear"></i> Website Meta Charset.</h5></legend>
              <div class="row align-middle">

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="meta_charset" type="text" class="form-control" id="floatingWebsiteMetaCharSet" placeholder="Meta Charset" value="<?php echo $get->meta_charset; ?>">
                    <label for="floatingWebsiteMetaCharSet">Meta Charset</label>
                  </div>
                </div>                               

              </div>
            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi bi-balloon"></i> Free mode</h5></legend>
              <div class="row align-middle">

              <div class="col-md-12">
                <div class="form-check form-switch mb-3 custom-switch">
                  <input class="form-check-input" type="checkbox" id="floatingFreemode" <?php if ($get->free_mode == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingFreemode', 'hiddenFreeMode')">
                  <input type="hidden" name="free_mode" id="hiddenFreeMode" value="<?php echo $get->free_mode; ?>">
                  <label class="form-check-label" for="floatingFreemode">Free mode</label>
                </div>
                <p>Activate this mode to make the website free, allowing users to converse with the intelligences without the need to purchase credits. However, logging in is still required for storing the chat history.</p>
              </div>                              

              </div>
            </fieldset>                        

          </div><!--nav-chat-options-->

          <div class="tab-pane fade" id="nav-header" role="tabpanel" aria-labelledby="nav-header-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
                  <legend><h5><i class="bi bi-moon-stars fs-4"></i> Dark Mode</h5></legend>

                  <div class="row">

                    <div class="col-md-12">
                      <div class="form-check form-switch mb-3 custom-switch">
                        <input class="form-check-input" type="checkbox" id="floatingShowDarkMode" <?php if ($get->allow_dark_mode == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingShowDarkMode', 'hiddenAllowDarkMode')">
                        <input type="hidden" name="allow_dark_mode" id="hiddenAllowDarkMode" value="<?php echo $get->allow_dark_mode; ?>">
                        <label class="form-check-label" for="floatingShowDarkMode">Display Dark mode icon in the header</label>
                        <i tabindex="-1" data-bs-toggle="tooltip" title="By using this option, the theme selection icon will be displayed in the website header, allowing the user to choose between the light and dark theme." class="bi-question-circle fs-5 text-success"></i>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-check form-switch mb-3 custom-switch">
                        <input class="form-check-input" type="checkbox" id="floatingForceDarkMode" <?php if ($get->force_dark_mode == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingForceDarkMode', 'hiddenForceDarkMode')">
                        <input type="hidden" name="force_dark_mode" id="hiddenForceDarkMode" value="<?php echo $get->force_dark_mode; ?>">
                        <label class="form-check-label" for="floatingForceDarkMode">Force use of dark mode</label> 
                        <i tabindex="-1" data-bs-toggle="tooltip" title="By using this option, the color selection icon in the header will be hidden, and the template will be set to always use the dark color." class="bi-question-circle fs-5 text-success"></i>
                    </div>

                  </div>

                </fieldset>

                <fieldset class="border rounded-2 p-3 mb-4">
                  <legend><h5><i class="bi bi-sticky"></i> Sticky header</h5></legend>

                  <div class="row">

                    <div class="col-md-12">
                      <div class="form-check form-switch mb-3 custom-switch">
                        <input class="form-check-input" type="checkbox" id="floatingShowStickyHeader" <?php if ($get->allow_sticky_header == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingShowStickyHeader', 'hiddenAllowStickyHeader')">
                        <input type="hidden" name="allow_sticky_header" id="hiddenAllowStickyHeader" value="<?php echo $get->allow_sticky_header; ?>">
                        <label class="form-check-label" for="floatingShowStickyHeader">Enable Sticky header</label>
                      </div>
                    </div>

                  </div>

                </fieldset>


                <fieldset class="border rounded-2 p-3 mb-4">
                  <legend><h5><i class="bi bi-box-arrow-in-right fs-4"></i> Show header on pages</h5></legend>

                  <div class="row">

                    <div class="col-md-12">
                      <div class="form-check form-switch mb-3 custom-switch">
                        <input class="form-check-input" type="checkbox" id="floatingHeaderSignUp" <?php if ($get->display_header_sign_up == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingHeaderSignUp', 'hideHeaderSignUp')">
                        <input type="hidden" name="display_header_sign_up" id="hideHeaderSignUp" value="<?php echo $get->display_header_sign_up; ?>">
                        <label class="form-check-label" for="floatingHeaderSignUp">Show header on sign up page</label>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-check form-switch mb-3 custom-switch">
                        <input class="form-check-input" type="checkbox" id="floatingHeaderSignIn" <?php if ($get->display_header_sign_in == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingHeaderSignIn', 'hiddenHeaderSignIn')">
                        <input type="hidden" name="display_header_sign_in" id="hiddenHeaderSignIn" value="<?php echo $get->display_header_sign_in; ?>">
                        <label class="form-check-label" for="floatingHeaderSignIn">Show header on sign in page</label> 
                    </div>

                  </div>

                </fieldset>     

          </div><!--nav-header-options-->

          <div class="tab-pane fade" id="nav-home-page" role="tabpanel" aria-labelledby="nav-home-page-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-list-ol fs-4"></i> Number of AI to show on home page</h5></legend>

              <div class="row align-middle">

                <div class="col-md-4">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayLoadMoreButton" <?php if ($get->display_load_more_button == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayLoadMoreButton', 'hiddenDisplayLoadMoreButton')">
                    <input type="hidden" name="display_load_more_button" id="hiddenDisplayLoadMoreButton" value="<?php echo $get->display_load_more_button; ?>">
                    <label class="form-check-label" for="floatingDisplayLoadMoreButton">Show "Load more" button</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="display_home_ai_number" type="text" class="form-control" id="floatingDisplayHomeAINumber" placeholder="AI number that will show on home" value="<?php echo isset($get->display_home_ai_number) ? $get->display_home_ai_number : ''; ?>">
                    <label for="floatingDisplayHomeAINumber">AI number that will show on home</label>
                  </div>
                </div>        

              </div>

            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-funnel fs-4"></i> View categories on the homepage</h5></legend>

              <div class="row align-middle">

                <div class="col-md-4">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayCategoriesHome" <?php if ($get->display_categories_home == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayCategoriesHome', 'hiddenDisplayCategoriesHome')">
                    <input type="hidden" name="display_categories_home" id="hiddenDisplayCategoriesHome" value="<?php echo $get->display_categories_home; ?>">
                    <label class="form-check-label" for="floatingDisplayCategoriesHome">Show categories on homepage</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="display_categories_home_number" type="text" class="form-control" placeholder="Number of categories displayed in the home" value="<?php echo isset($get->display_categories_home_number) ? $get->display_categories_home_number : ''; ?>">
                    <label>Number of categories displayed in the home</label>
                  </div>
                </div>                         

              </div>
            </fieldset>

            <fieldset>
              <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-palette fs-4"></i> Theme colors</h5></legend>
              
                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayUseCustomColorTheme" <?php if ($get->use_custom_color_theme == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayUseCustomColorTheme', 'hiddenDisplayCustomColorTheme')">
                    <input type="hidden" name="use_custom_color_theme" id="hiddenDisplayCustomColorTheme" value="<?php echo $get->use_custom_color_theme; ?>">
                    <label class="form-check-label" for="floatingDisplayUseCustomColorTheme">Use Theme colors</label>
                  </div>        
                </div> 

                <p>Enable/Disable css from <a href="<?php echo $base_url; ?>/admin/theme">theme</a> menu</p>

            </fieldset>

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-funnel fs-4"></i> Cookie message</h5></legend>

              <div class="row align-middle">

                <div class="col-md-4">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayCookieMessage" <?php if ($get->display_cookies_alert == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayCookieMessage', 'hiddenDisplayCookieMessage')">
                    <input type="hidden" name="display_cookies_alert" id="hiddenDisplayCookieMessage" value="<?php echo $get->display_cookies_alert; ?>">
                    <label class="form-check-label" for="floatingDisplayCookieMessage">Show Cookie Message</label>
                  </div>
                  <p>Use the <a href="<?php echo $base_url."/admin/languages"; ?>">translate</a> module if you want to customize the message. <br> The popup will not appear after you accept the terms.</p>
                </div>    

              </div>
            </fieldset>

            
          </div><!--nav-home-page-options-->

          <div class="tab-pane fade" id="nav-email-configs" role="tabpanel" aria-labelledby="nav-email-configs-tab" tabindex="0">
          
          <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-envelope"></i> SMTP Configs</h5></legend>
                <div class="row align-middle">

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <select name="smtp_auth" class="form-control" id="floatingSMTPAuth" required>
                        <option value="1" <?php if (isset($get->smtp_auth) && $get->smtp_auth == '1') echo 'selected'; ?>>Yes</option>
                        <option value="0" <?php if (isset($get->smtp_auth) && $get->smtp_auth == '0') echo 'selected'; ?>>No</option>
                      </select>                 
                      <label for="floatingSMTPAuth">SMTP Authentication</label>
                    </div>                 
                  </div>                   

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="smtp_charset" type="text" class="form-control" id="floatingSMTPCharset" placeholder="SMTP Charset" value="<?php echo isset($get->smtp_charset) ? $get->smtp_charset : ''; ?>">
                      <label for="floatingSMTPCharset">SMTP Charset</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="smtp_port" type="number" class="form-control" id="floatingSMTPPort" placeholder="SMTP Port" value="<?php echo isset($get->smtp_port) ? $get->smtp_port : ''; ?>">
                      <label for="floatingSMTPPort">SMTP Port</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="smtp_host" type="text" class="form-control" id="floatingSMTPHost" placeholder="SMTP Host" value="<?php echo isset($get->smtp_host) ? $get->smtp_host : ''; ?>">
                      <label for="floatingSMTPHost">SMTP Host</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="smtp_username" type="text" class="form-control" id="floatingSMTPUsername" placeholder="SMTP Username" value="<?php echo isset($get->smtp_username) ? $get->smtp_username : ''; ?>">
                      <label for="floatingSMTPUsername">SMTP Username</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <?php if($config->demo_mode){?>
                      <div class='col-md-12'><div class="alert alert-info"><i class="bi bi-exclamation-octagon fs-4"></i> For security reasons, the password is not shown in demo mode.</div></div>
                    <?php }else{ ?> 
                      <div class="form-floating mb-3">
                        <input name="smtp_password" type="password" class="form-control" id="floatingSMTPPassword" placeholder="SMTP Password" value="<?php echo isset($get->smtp_password) ? $get->smtp_password : ''; ?>">
                        <label for="floatingSMTPPassword">SMTP Password</label>
                      </div>
                    <?php } ?>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="smtp_from" type="email" class="form-control" id="floatingSMTPFrom" placeholder="SMTP From" value="<?php echo isset($get->smtp_from) ? $get->smtp_from : ''; ?>">
                      <label for="floatingSMTPFrom">SMTP From</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <input name="smtp_from_name" type="email" class="form-control" id="floatingSMTPFromName" placeholder="SMTP From Name" value="<?php echo isset($get->smtp_from_name) ? $get->smtp_from_name : ''; ?>">
                      <label for="floatingSMTPFromName">SMTP From Name</label>
                    </div>
                  </div>


                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <select name="smtp_verify_peer" class="form-control" id="floatingSMTPVerifyPeer" required>
                        <option value="1" <?php if (isset($get->smtp_verify_peer) && $get->smtp_verify_peer == '1') echo 'selected'; ?>>True</option>
                        <option value="0" <?php if (isset($get->smtp_verify_peer) && $get->smtp_verify_peer == '0') echo 'selected'; ?>>False</option>
                      </select>
                      <label for="floatingSMTPVerifyPeer">SMTP Verify Peer</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <select name="smtp_verify_peer_name" class="form-control" id="floatingSMTPVerifyPeerName" required>
                        <option value="1" <?php if (isset($get->smtp_verify_peer_name) && $get->smtp_verify_peer_name == '1') echo 'selected'; ?>>True</option>
                        <option value="0" <?php if (isset($get->smtp_verify_peer_name) && $get->smtp_verify_peer_name == '0') echo 'selected'; ?>>False</option>
                      </select>
                      <label for="floatingSMTPVerifyPeerName">SMTP Verify Peer Name</label>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <select name="smtp_allow_self_signed" class="form-control" id="floatingSMTPAllowSelfSigned" required>
                        <option value="1" <?php if (isset($get->smtp_allow_self_signed) && $get->smtp_allow_self_signed == '1') echo 'selected'; ?>>True</option>
                        <option value="0" <?php if (isset($get->smtp_allow_self_signed) && $get->smtp_allow_self_signed == '0') echo 'selected'; ?>>False</option>
                      </select>
                      <label for="floatingSMTPAllowSelfSigned">SMTP Allow Self-Signed</label>
                    </div>
                  </div>   

                  <div class="col-md-4">
                    <div class="form-floating mb-3">
                      <select name="smtp_secure" class="form-control" id="floatingSMTPSecure" required>
                        <option value="*" <?php if (isset($get->smtp_secure) && $get->smtp_secure == '') echo 'selected'; ?>>None</option>
                        <option value="tls" <?php if (isset($get->smtp_secure) && $get->smtp_secure == 'tls') echo 'selected'; ?>>TLS</option>
                        <option value="ssl" <?php if (isset($get->smtp_secure) && $get->smtp_secure == 'ssl') echo 'selected'; ?>>SSL</option>
                      </select>
                      <label for="floatingSMTPSecure">SMTPSecure</label>
                    </div>
                  </div>                

                  <div class="col-12">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTestSMTP">Test sending email</button>
                  </div>

                </div>
              </fieldset>

              <fieldset class="border rounded-2 p-3 mb-4">
                <legend><h5><i class="bi bi-envelope"></i> Email Template - Password recovery code</h5></legend>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="recovery_code_subject" type="text" class="form-control" id="floatingSubjectRecoveryPassword" placeholder="Subject" value="<?php echo isset($get->recovery_code_subject) ? $get->recovery_code_subject : ''; ?>">
                    <label for="floatingSubjectRecoveryPassword">Subject</label>
                  </div>
                </div>

                <p>Use the <b>{{code}}</b> tag in the template to display the code.</p>
                <div class="form-group">
                  <textarea class="editor" name="email_template_recovery_code"><?php echo isset($get->email_template_recovery_code) ? $get->email_template_recovery_code : ''; ?></textarea>
                </div>
            </fieldset>


          </div><!--nav-email-configs-tab-->

          <div class="tab-pane fade" id="nav-maintenance-mode" role="tabpanel" aria-labelledby="nav-maintenance-mode-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-tools"></i> Activate maintenance mode <i tabindex="-1" data-bs-toggle="tooltip" title="Maintenance mode lets you stop site services while you make adjustments or test your prompts. However, if you are logged in as an administrator, you will still have access to the site." class="bi-question-circle fs-5 text-success"></i></h5></legend>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingMaintenanceMode" <?php if ($get->maintenance_mode == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingMaintenanceMode', 'hiddenMaintenanceMode')">
                    <input type="hidden" name="maintenance_mode" id="hiddenMaintenanceMode" value="<?php echo $get->maintenance_mode; ?>">
                    <label class="form-check-label" for="floatingMaintenanceMode">Enable/Disable maintenance mode.</label>
                  </div>
                </div>

              <div class="form-group">
                <textarea class="editor" name="maintenance_mode_text"><?php echo isset($get->maintenance_mode_text) ? $get->maintenance_mode_text : ''; ?></textarea>
              </div>        

              </div>

            </fieldset>  
            
            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-chat"></i> Show/hide php errors and notices (just for debug purposes)</h5></legend>

              <div class="row">

                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingShowPHPErrors" <?php if ($get->php_errors == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingShowPHPErrors', 'hiddenPhpErros')">
                    <input type="hidden" name="php_errors" id="hiddenPhpErros" value="<?php echo $get->php_errors; ?>">
                    <label class="form-check-label" for="floatingShowPHPErrors">Show/hide PHP errors</label>
                  </div>
                </div>

              </div>

            </fieldset>
            
          </div><!--nav-maintenance-mode-tab-->

          <div class="tab-pane fade" id="nav-google-analytics" role="tabpanel" aria-labelledby="nav-google-analytics-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-bar-chart"></i> Google Analytics</h5></legend>
              <div class="row align-middle">


                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayUseGoogleAnalytics" <?php if ($get->use_google_analytics == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayUseGoogleAnalytics', 'hiddenDisplayUseGoogleAnalytics')">
                    <input type="hidden" name="use_google_analytics" id="hiddenDisplayUseGoogleAnalytics" value="<?php echo $get->use_google_analytics; ?>">
                    <label class="form-check-label" for="floatingDisplayUseGoogleAnalytics">Use Google Analytics</label>
                  </div>        
                </div>


                <div class="col-md-12">
                  <div class="mb-3">
                    <textarea style="height: 150px" name="google_analytics_code" class="form-control format-code" placeholder="Google Analytics Code"><?php echo htmlspecialchars($get->google_analytics_code, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
                </div>

              </div>
            </fieldset>
            
          </div><!--nav-google-analytics-tab-->

          <div class="tab-pane fade" id="nav-dalle" role="tabpanel" aria-labelledby="nav-dalle-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-card-image"></i> DALL·E 2 Configs</h5></legend>
              <div class="row align-middle">

                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <input name="dalle_generated_img_count" type="number" class="form-control" id="floatingDallEImgCount" placeholder="Number of images" value="<?php echo $get->dalle_generated_img_count; ?>" required>
                    <label for="floatingDallEImgCount">Number of images</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <input name="dalle_spend_credits" type="number" class="form-control" id="floatingDallESpent" placeholder="Amount of credits to be spent to generate the image pack" value="<?php echo $get->dalle_spend_credits; ?>" required>
                    <label for="floatingDallESpent">Amount of credits to be spent to generate the image pack</label>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-floating mb-3">
                    <select name="dalle_img_size" class="form-control" id="floatingDallEImgSize" required>
                      <?php
                      $dalle_img_size1 = ['256x256', '512x512','1024x1024'];
                      $selectedDalleImgSize = $get->dalle_img_size;

                      foreach ($dalle_img_size1 as $dalle_img_size) {
                        $selected = $dalle_img_size == $selectedDalleImgSize ? 'selected' : '';
                        echo "<option value='$dalle_img_size' $selected>$dalle_img_size</option>";
                      }
                      ?>
                    </select>                 
                    <label for="floatingDallEImgSize">Dall-E Image Size</label>
                  </div>                 
                </div>

              </div>                                  

            </fieldset>
            
          </div><!--nav-dalle-tab-->

          <div class="tab-pane fade" id="nav-bank-info" role="tabpanel" aria-labelledby="nav-bank-info-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-bank"></i> Payment method - Bank deposit</h5></legend>
              <div class="row align-middle">


                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayBankDepositeActive" <?php if ($get->bank_deposit_active == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayBankDepositeActive', 'hiddenDisplayBankDepositActive')">
                    <input type="hidden" name="bank_deposit_active" id="hiddenDisplayBankDepositActive" value="<?php echo $get->bank_deposit_active; ?>">
                    <label class="form-check-label" for="floatingDisplayBankDepositeActive">Use bank deposit method</label>
                  </div>        
                </div>

                <div class="col-md-12">
                  <textarea class="editor" name="bank_deposit_info"><?php echo htmlspecialchars($get->bank_deposit_info, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

              </div>
            </fieldset>

          </div><!--nav-bank-info-tab-->

          <div class="tab-pane fade" id="nav-custom-code-js" role="tabpanel" aria-labelledby="nav-custom-code-js-tab" tabindex="0">

            <div class="row">
              <div class="col-12">
            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-filetype-js"></i> Custom javascript code</h5></legend>
              <p><b>Add your codes including the &lt;script&gt; tag</b>. <br>When adding custom codes, it is essential to proceed with caution as they can impact the functionality of your website. By default, these codes are added to the footer of all pages.</p>
              <div class="row align-middle">


                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayUseJscode" <?php if ($get->use_custom_code == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayUseJscode', 'hiddenDisplayUseJscode')">
                    <input type="hidden" name="use_custom_code" id="hiddenDisplayUseJscode" value="<?php echo $get->use_custom_code; ?>">
                    <label class="form-check-label" for="floatingDisplayUseJscode">Use custom code</label>
                  </div>        
                </div>


                <div class="col-md-12">
                  <div class="mb-3">
                    <textarea style="height: 150px" name="custom_code" class="form-control format-code" placeholder="Custom code"><?php echo htmlspecialchars($get->custom_code, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
                </div>

              </div>
            </fieldset>   
                                    
              </div>
            </div>

          </div><!--nav-custom-code-js-tab-->

          <div class="tab-pane fade" id="nav-custom-code-css" role="tabpanel" aria-labelledby="nav-custom-code-css-tab" tabindex="0">

            <div class="row">
              <div class="col-12">
            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-filetype-css"></i> Custom CSS code</h5></legend>

              <div class="row align-middle">


                <div class="col-md-12">
                  <div class="mb-3">
                    <textarea style="height: 150px" name="custom_code_css" class="form-control format-code" placeholder="Custom code"><?php echo htmlspecialchars($get->custom_code_css, ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>
                </div>

              </div>
            </fieldset>   
                                    
              </div>
            </div>

          </div><!--nav-custom-code-js-tab--> 

          <div class="tab-pane fade" id="nav-blog-sidebar" role="tabpanel" aria-labelledby="nav-blog-sidebar-tab" tabindex="0">

            <div class="row">
              <div class="col-12">
                <fieldset class="border rounded-2 p-3 mb-4">
                  <legend><h5><i class="bi bi-bookmark"></i> Blog</h5></legend>

                  <div class="col-md-12">
                    <div class="form-floating mb-3">
                      <input name="blog_pagination" type="number" class="form-control" id="floatingBlogPostsPagination" placeholder="Number of posts for pagination" value="<?php echo $get->blog_pagination; ?>" required>
                      <label for="floatingBlogPostsPagination">Number of posts for pagination</label>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="form-group">
                      <textarea class="editor" name="blog_sidebar"><?php echo htmlspecialchars($get->blog_sidebar, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>   
                  </div>
                </fieldset>   
                                    
              </div>
            </div>

          </div><!--nav-custom-code-js-tab-->

          <div class="tab-pane fade" id="nav-admin-login" role="tabpanel" aria-labelledby="nav-admin-login-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi bi-shield-check"></i> Extra security options</h5></legend>
              <p>Actions for when admin logs in.</p>
              <div class="row">

                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">

                    <input class="form-check-input" type="checkbox" id="floatingCheckAdminAgent" <?php if ($get->admin_check_user_agent == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingCheckAdminAgent', 'hiddenCheckAdminAgent')">
                    <input type="hidden" name="admin_check_user_agent" id="hiddenCheckAdminAgent" value="<?php echo $get->admin_check_user_agent; ?>">
                    <label class="form-check-label" for="floatingCheckAdminAgent">Check admin agent</label>
                    <i tabindex="-1" data-bs-toggle="tooltip" title="Check Admin Agent: Verifies the browser used by the admin. Helps detect unexpected browser changes, enhancing security." class="bi-question-circle fs-5 text-success"></i>                    
                  </div>                  
                </div>

                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingCheckIp" <?php if ($get->admin_check_ip_address == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingCheckIp', 'hiddenCheckAdminIp')">
                    <input type="hidden" name="admin_check_ip_address" id="hiddenCheckAdminIp" value="<?php echo $get->admin_check_ip_address; ?>">
                    <label class="form-check-label" for="floatingCheckIp">Check admin IP</label>
                    <i tabindex="-1" data-bs-toggle="tooltip" title="Check Admin IP: Checks the admin's IP address. Detects location changes, providing an additional layer of security." class="bi-question-circle fs-5 text-success"></i>                      
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingCheckAdminToken" <?php if ($get->admin_check_token == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingCheckAdminToken', 'hiddenCheckAdminToken')">
                    <input type="hidden" name="admin_check_token" id="hiddenCheckAdminToken" value="<?php echo $get->admin_check_token; ?>">
                    <label class="form-check-label" for="floatingCheckAdminToken">Check admin Token</label>
                    <i tabindex="-1" data-bs-toggle="tooltip" title="Check Admin Token: Checks a unique identifier assigned to the admin at login. Ensures the session is valid and enhances security against session hijacking attacks." class="bi-question-circle fs-5 text-success"></i>                     
                  </div>
                </div>                                              

              </div>

            </fieldset>            

          </div><!--nav-admin-login-tab-->

          <div class="tab-pane fade" id="nav-cache" role="tabpanel" aria-labelledby="nav-cache-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-stars"></i> Cache</h5></legend>
              <p>To force a new cache, you can update the version of the CSS and JavaScript files. <br>The cache files that will affect the versioning are "style/app.css" and "js/main.js".</p>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="css_version" type="number" class="form-control" id="floatingCacheCSS" placeholder="Caching version for the CSS" value="<?php echo $get->css_version; ?>">
                    <label for="floatingCacheCSS">Caching version for the CSS</label>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="js_version" type="number" class="form-control" id="floatingCacheJS" placeholder="Caching version for the CSS" value="<?php echo $get->js_version; ?>">
                    <label for="floatingCacheJS">Caching version for the JS</label>
                  </div>
                </div>                                    
                                          

              </div>

            </fieldset>            

          </div><!--nav-cache-->

          <div class="tab-pane fade" id="nav-vip" role="tabpanel" aria-labelledby="nav-vip-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-star-fill"></i> Vip</h5></legend>
              <div class="row">

                  <div class="col-md-12">
                    <div class="form-check form-switch mb-3 custom-switch">
                      <input class="form-check-input" type="checkbox" id="floatingDisplayVipHigherTier" <?php if ($get->vip_higher_tier == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayVipHigherTier', 'hiddenDisplayVipHigherTier')">
                      <input type="hidden" name="vip_higher_tier" id="hiddenDisplayVipHigherTier" value="<?php echo $get->vip_higher_tier; ?>">
                      <label class="form-check-label" for="floatingDisplayVipHigherTier">Use the higher purchase tier</label>
                    </div>        
                  </div>        

                <div class="col-12">
                  <p>By checking the option above, you activate the "Higher Tier" function.<br>
                  When this function is enabled, the system will consider the highest tier level that the user has purchased to grant access to the bots.<br>
                  For example, suppose a bot requires tier 1 and tier 2 to be accessed, but the user purchased a package with tier 3 or higher. <br>
                  In this case, if the "Higher Tier" option is enabled, the user will automatically have access to tiers 1 and 2, even though they did not individually purchase those tiers. <br>
                This happens because the system takes into account the highest tier level that the user possesses, granting them access to all lower tiers as well.</p>                  
                </div>

                <div class="col-md-12">
                  <hr>
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayPromptsPackageList" <?php if ($get->display_prompts_packagelist == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayPromptsPackageList', 'hiddenDisplayPromptsPackageList')">
                    <input type="hidden" name="display_prompts_packagelist" id="hiddenDisplayPromptsPackageList" value="<?php echo $get->display_prompts_packagelist; ?>">
                    <label class="form-check-label" for="floatingDisplayPromptsPackageList">Display prompts on the price screen</label>
                  </div>        
                </div> 

                <div class="col-md-12">
                  <hr>
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayFreePrompts" <?php if ($get->vip_display_free_prompts == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayFreePrompts', 'hiddenDisplayFreePrompts')">
                    <input type="hidden" name="vip_display_free_prompts" id="hiddenDisplayFreePrompts" value="<?php echo $get->vip_display_free_prompts; ?>">
                    <label class="form-check-label" for="floatingDisplayFreePrompts">Display free prompts in package</label>
                  </div>        
                </div>                

              </div>

            </fieldset>            

          </div><!--nav-vip-tab-->

          <div class="tab-pane fade" id="nav-customer" role="tabpanel" aria-labelledby="nav-customer-tab" tabindex="0">

            <fieldset class="border rounded-2 p-3 mb-4">
              <legend><h5><i class="bi bi-people"></i> Customer - Validate registration</h5></legend>
              <div class="row">

                <div class="col-md-12">
                  <p>By selecting the option below, you will be requiring new users to confirm their accounts by clicking on a link sent through email.</p>
                  <div class="form-check form-switch mb-3 custom-switch">
                    <input class="form-check-input" type="checkbox" id="floatingDisplayCustomerConfirmEmail" <?php if ($get->customer_confirm_email == 1) { echo 'checked'; } ?> onchange="updateSwitchValue('floatingDisplayCustomerConfirmEmail', 'hiddenDisplayCustomerConfirmEmail')">
                    <input type="hidden" name="customer_confirm_email" id="hiddenDisplayCustomerConfirmEmail" value="<?php echo $get->customer_confirm_email; ?>">
                    <label class="form-check-label" for="floatingDisplayCustomerConfirmEmail">Confirm email on registration</label>
                  </div>        
                </div>

                <div class="col-md-12">
                  <div class="form-floating mb-3">
                    <input name="customer_confirm_email_subject" type="text" class="form-control" id="floatingSubjectCustomerConfirmEmail" placeholder="Subject" value="<?php echo isset($get->customer_confirm_email_subject) ? $get->customer_confirm_email_subject : ''; ?>">
                    <label for="floatingSubjectCustomerConfirmEmail">Subject</label>
                  </div>
                  <hr>
                </div>

                <h5>Email content:</h5>
                <p>Use the <b>{{link}}</b> tag in the template to display the confirmation link.<br>
                Use <b>{{name}}</b> to display the user's name (optional).
                </p>
                <div class="form-group">
                  <textarea class="editor" name="customer_confirm_email_content"><?php echo isset($get->customer_confirm_email_content) ? $get->customer_confirm_email_content : ''; ?></textarea>
                </div>                        

              </div>

            </fieldset>            

          </div><!--nav-customer-tab-->


          <!--<div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-header-tab" tabindex="0">3...</div>-->
        </div>

      </div>
    </div>

                              


    <div class="d-grid">
      <button class="btn btn-success text-uppercase fw-bold mb-2 submit-button" type="submit">Save</button>
    </div>
    <input type="hidden" name="url_hash" id="settings_url_hash" value="nav-api-key-tab">
    <input type="hidden" name="id" value="1">
    <input type="hidden" name="action" value="edit">
  </form>
  </div>


  <div id="formErrorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <i class="bi bi-exclamation-octagon"></i>Attention: Please check all mandatory fields.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>      

<?php
require_once(__DIR__."/../../inc/footer.php");
?>