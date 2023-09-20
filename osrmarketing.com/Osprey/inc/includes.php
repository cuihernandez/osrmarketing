<?php 
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once(__DIR__ . '/../admin/inc/Autoloader.php');
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../inc/functions.php');

// Instantiate required classes
$badwords = new Badwords();
$categories = new Categories();
$credits_packs = new CreditsPacks();
$customer_credits_packs = new CustomerCreditsPacks();
$prompts_credits_packs = new PromptsCreditsPacks();
$customers = new Customers();
$languages = new Languages();
$menus = new Menus();
$messages = new Messages();
$pages = new Pages();
$posts = new Posts();
$posts_tags = new PostsTags();
$prompts = new Prompts();
$prompts_categories = new PromptsCategories();
$prompts_output = new PromptsOutput();
$prompts_tone = new PromptsTone();
$prompts_writing = new PromptsWriting();
$seo = new Seo();
$settings = new Settings();
$tags = new Tags();
$theme = new Theme();

// Get configuration settings
$config = $settings->get(1);
if (isset($config) && is_object($config)) {
    ini_set('display_errors', $config->php_errors);
    ini_set('display_startup_errors', $config->php_errors);
    error_reporting($config->php_errors ? E_ALL : 0);
}

// Get SEO configs
$getSeo = $seo->get(1);
$seoConfig = json_decode($getSeo->seo_options, true);
foreach ($seoConfig as $key => $value) {
    $seoConfig[$key] = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
}

// Set salt number
$saltnumber = 2545215614;

// Get list of front pages
$getPages = $pages->getListFront();

// Get theme options
$getTheme = $theme->get(1);

// Decode theme options JSON
$theme_skin = json_decode($getTheme->theme_options, true);

// Process Unicode escape sequences in theme options
foreach ($theme_skin as $key => $value) {
    $theme_skin[$key] = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
}

// Get default language and its translations
$defaultLanguage = $languages->get(1);
$langEN = processTranslations(json_decode($defaultLanguage->translations, true));

// Get default language or fallback to English if not available
$getDefaultLanguage = $languages->getListDefault();
$lang = $getDefaultLanguage->lang != "en" ? $languages->get($getDefaultLanguage->id) : $defaultLanguage;

// Process translations for selected language
$lang = processTranslations(json_decode($lang->translations, true));

// Merge English translations with selected language translations
$lang = array_merge_check_empty($langEN, $lang);

// Get base URL
$base_url = url();
$base_url = $base_url;

if ($getDefaultLanguage->lang === 'ar') {
  $dir = 'rtl';
  $bootstrapCSS = '/style/bootstrap.rtl.min.css';
} else {
  $dir = 'ltr';
  $bootstrapCSS = $base_url . '/style/bootstrap.min.css';
}

// Initialize customer messages count
$customerMessagesCount = 0;

// Calculate customer messages count from session history
if (isset($_SESSION['history']) && is_array($_SESSION['history'])) {
    $customerMessagesCount = array_reduce($_SESSION['history'], function ($count, $conversation) {
        return $count + count(array_filter($conversation, function ($message) {
            return $message['role'] === 'user';
        }));
    }, 0);
}

// Check if user is logged in
if (!isset($_SESSION['id_customer']) && empty($_SESSION['id_customer'])) {
    $isLogged = false;
    if (!isset($_SESSION['message_count'])) {
        $_SESSION['message_count'] = 1;
    }    
} else {
    $isLogged = true;
    $userCredits = $customers->get($_SESSION['id_customer']);
    $user_credit_pack = getCustomerCreditPack($_SESSION['id_customer']);
    if($userCredits){
        $userCredits = $userCredits->credits;
    }else{
        header("location:/logout");
    }

    // Ensure user credits are not negative
    if ($userCredits < 0) {
        $customers->setCredits($_SESSION['id_customer'], 0);
        $userCredits = 0;
    }
}

// Set Stripe API key if Stripe is being used
if (isset($use_stripe) && $use_stripe) {
    // Define the Stripe API secret key
    if ($config->stripe_test_mode) {
        \Stripe\Stripe::setApiKey($config->stripe_api_key_test);
    } else {
        \Stripe\Stripe::setApiKey($config->stripe_api_key_production);
    }
}

if ($isLogged) {
    $getCustomer = $customers->getCustomer($_SESSION['id_customer']);
}

// Check maintenance mode
if($config->maintenance_mode){
    // Check if admin session exists
    if (!isset($_SESSION['admin_id'])) {
        if(!$maintenance_mode){
            header("Location:".$base_url."/maintenance");
            exit(); // Ensure that the script exits after the redirect
        }
    } else {
    }
}

function showVipCard($idPrompt){
    global $prompts, $lang, $isLogged, $credits_packs, $user_credit_pack;

    $vipCheckResult = $isLogged ? checkLoggedInVipStatus($idPrompt, $prompts, $credits_packs, $user_credit_pack) : checkLoggedOutVipStatus($idPrompt, $prompts);

    if($vipCheckResult){
        echo '<div class="card-vip"><img src="/img/icon-vip.svg" alt="'.$lang['vip_label'].'" title="'.$lang['vip_label'].'"><span>'.$lang['vip_label'].'</span></div>';
    }
}
