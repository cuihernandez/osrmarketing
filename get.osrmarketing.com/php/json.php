<?php
require_once(__DIR__ . '/../admin/inc/includes.php');

//Get json prompt
if($_GET['action'] == "prompt"){
    $id = addslashes($_GET['id']);
    if(is_numeric($id)){
        $showPrompts = $prompts->get($id);
        if(!$showPrompts->id){
        die("Error finding ID");            
        die();
        }
        $jsonOutput = [];

        $jsonOutput[] = [
            'id' => isset($showPrompts->id) ? $showPrompts->id : null,
            'name' => isset($showPrompts->name) ? $showPrompts->name : '',
            'slug' => isset($showPrompts->slug) ? $showPrompts->slug : '',
            'image' => isset($showPrompts->image) ? 'public_uploads/' . $showPrompts->image : '',
            'mic_speak_lang' => isset($showPrompts->mic_speak_lang) ? $showPrompts->mic_speak_lang : 'en-US',
            'chat_minlength' => isset($showPrompts->chat_minlength) ? (int) $showPrompts->chat_minlength : 0,
            'chat_maxlength' => isset($showPrompts->chat_maxlength) ? (int) $showPrompts->chat_maxlength : 0,
            'API_MODEL' => isset($showPrompts->API_MODEL) ? $showPrompts->API_MODEL : '',
            'use_google_voice' => isset($showPrompts->use_google_voice) ? $showPrompts->use_google_voice : '',
            'use_cloud_google_voice' => isset($showPrompts->use_cloud_google_voice) ? $showPrompts->use_cloud_google_voice : 0,
            'display_mp3_google_cloud_text' => isset($showPrompts->display_mp3_google_cloud_text) ? $showPrompts->display_mp3_google_cloud_text : 0,
            'display_mp3_google_cloud_text' => isset($showPrompts->display_mp3_google_cloud_text) ? $showPrompts->display_mp3_google_cloud_text : 0,
            'cloud_google_voice' => isset($showPrompts->cloud_google_voice) ? $showPrompts->cloud_google_voice : '',
            'cloud_google_voice_lang_code' => isset($showPrompts->cloud_google_voice_lang_code) ? $showPrompts->cloud_google_voice_lang_code : '',
            'cloud_google_voice_gender' => isset($showPrompts->cloud_google_voice_gender) ? $showPrompts->cloud_google_voice_gender : '',
            'google_voice' => isset($showPrompts->google_voice) ? $showPrompts->google_voice : '',
            'google_voice_lang_code' => isset($showPrompts->google_voice_lang_code) ? $showPrompts->google_voice_lang_code : '',
            'display_microphone_in_chat' => isset($showPrompts->display_mic) ? (int) $showPrompts->display_mic : 0,
            'display_avatar' => isset($showPrompts->display_avatar) ? (int) $showPrompts->display_avatar : 0,
            'display_copy_btn' => isset($showPrompts->display_copy_btn) ? (int) $showPrompts->display_copy_btn : 0,
            'display_description' => isset($showPrompts->display_description) ? (int) $showPrompts->display_description : 0,
            'filter_badwords' => isset($showPrompts->filter_badwords) ? (int) $showPrompts->filter_badwords : 0,
            'use_dalle' => isset($showPrompts->use_dalle) ? (int) $showPrompts->use_dalle : 0
        ];
        header('Content-Type: application/json');
        echo json_encode($jsonOutput, JSON_PRETTY_PRINT);
        die();
    }else{
        die("Error finding ID");
    }
}

if($_GET['action'] == "language"){
    function decodeUnicodeEscapeSequences($matches) {
        return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UCS-2BE');
    }

    // Get default language and its translations
    $defaultLanguage = $languages->get(1);
    $langEN = json_decode($defaultLanguage->translations, true);

    // Get default language or fallback to English if not available
    $getDefault = $languages->getListDefault();
    $lang = $getDefault->lang != "en" ? $languages->get($getDefault->id) : $defaultLanguage;

    // Process translations for selected language
    $lang = json_decode($lang->translations, true);

    // Merge English translations with selected language translations
    $lang = array_merge($langEN, $lang);
    
    $jsonOutput = [];
    foreach ($lang as $key => $value) {
        $decodedValue = preg_replace_callback('/u([0-9a-fA-F]{4})/', 'decodeUnicodeEscapeSequences', $value);
        $jsonOutput[] = [$key => $decodedValue];
    }
    
    header('Content-Type: application/json');
    echo json_encode($jsonOutput, JSON_PRETTY_PRINT);
}
if($_GET['action'] == "badwords"){
    $getBadWords = $badwords->get(1);
    $jsonData = json_encode(array("badwords" => $getBadWords->badwords));
    echo $jsonData;
}