<?php
// Check if the session has been started, and if not, start it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the request is a fetch request and if it's true
$isFetchRequest = isset($_POST['isFetchRequest']) && $_POST['isFetchRequest'] === 'true';

// If it's a fetch request, retrieve the data from the database
if ($isFetchRequest) {
    require_once("../../inc/includes.php");
    $AI = $prompts->getBySlug($_REQUEST['slug']);

    // Check if the user is logged in
    $isLogged = isset($_SESSION['id_customer']) && !empty($_SESSION['id_customer']);
}

// If the user is logged in, update the session history with the messages
if ($isLogged && !@$share) {
    function updateSessionHistory($AI_id, $messagesDatabase)
    {
        // Create an empty array for the history of the current AI
        $_SESSION["history"][$AI_id] = [];

        // Loop through each message and add it to the history
        foreach ($messagesDatabase as $message) {
            $_SESSION["history"][$AI_id][] = [
                'item_order' => (int)$message->item_order,
                'id_message' => $message->id_message,
                'role' => $message->role,
                'content' => $message->content,
                'dall_e_array' => $message->dall_e_array,
                'datetime' => $message->created_at,
                'total_characters' => $message->total_characters,
                'saved' => (bool)$message->saved,
            ];
        }
    }

    // Check if it's a fetch request
    if ($isFetchRequest) {
        @$thread_id = @$_SESSION['threads'][$AI->id];
    } else {
        // If the thread array is not set in the session, create it
        if (!isset($_SESSION['threads'])) {
            $_SESSION['threads'] = [];
        }

        // If the thread ID is not set for the current AI, generate a new ID and save it in the session
        if (!array_key_exists($AI->id, $_SESSION['threads'])) {
            $thread_id = threadNewID();
            $_SESSION['threads'][$AI->id] = $thread_id;
        } else {
            // If the thread ID is set for the current AI, retrieve it from the session
            if(isset($getTargetThread)){
            	$thread_id = $getTargetThread;
            }else{
            	$thread_id = $_SESSION['threads'][$AI->id];
            }
        }
    }

    // Check if there are any unsaved messages in the session history
    if (isset($_SESSION["history"][$AI->id])) {
        $hasError = false;
        $hasItem = false;

        // Loop through each message in the history and save it to the database if it's unsaved
        foreach ($_SESSION["history"][$AI->id] as $message) {
            if ($message["saved"] === false) {
                $_POST['id_message'] = $message['id_message'];
                $_POST['id_thread'] = $thread_id;
                $_POST['id_customer'] = $_SESSION["id_customer"];
                $_POST['id_prompt'] = $AI->id;
                $_POST['role'] = $message['role'];
                $_POST['content'] = $message['content'];
                if (isset($message['dall_e_array'])) {
                    $_POST['dall_e_array'] = $message['dall_e_array'];
                }
                $_POST['item_order'] = $message['item_order'];
                $_POST['saved'] = 1;
                if (isset($message['total_characters'])) {
                    $_POST['total_characters'] = $message['total_characters'];
                }
                $messages = new Messages();
                if ($messages->add()) {
                    $hasItem = true;
                } else {
                    $hasError = true;
                }
            }
        }
    }

    $checkMessageDb = $messages->getByThread($thread_id)->Fetch();

    if (isset($checkMessageDb->id)) {
        $getMessagesDatabase = $messages->getByThread($thread_id);
        updateSessionHistory($AI->id, $getMessagesDatabase);
    } else {
        if (isset($_SESSION["history"][$AI->id])) {
            unset($_SESSION["history"][$AI->id]);
        }
    }

	if($isFetchRequest){
	    if($hasError){
	      echo json_encode(array('success' => false, 'message' => 'Error saving data'));
	    }
	    if($hasItem){
	      echo json_encode(array('success' => true, 'message' => 'Data successfully saved'));
	    }else{
	      echo json_encode(array('success' => true, 'message' => 'No new data to be updated'));
	    }  	
	}  

}else{
  if (isset($_SESSION["history"])) {
    unset($_SESSION["history"]);
  }

	if($isFetchRequest){
		echo json_encode(array('success' => true, 'message' => 'No new data to be updated'));
	}  
}