<?php 
ob_start();
require_once("../../inc/includes.php");
//error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
$share = false;
if(isset($_GET['share']) && $_GET['share']){
    $share = true;
}


if(!$isLogged){
    if(!$share){
	   redirect($base_url.'/chat/'.$_REQUEST['ai'], $lang['login_prompt_downloading_file'], 'error');
    }
}


$getMessages = $messages->getByThread($_REQUEST['thread']);
$checkAIName = $getMessages->fetch();


if(!$checkAIName){
	redirect($base_url.'/chat/'.$_REQUEST['ai'], "Thread not found", 'error');	
}


$getCustomer = $customers->get($checkAIName->id_customer);
$getAI = $prompts->get($checkAIName->id_prompt);


// Nomes personalizados
$ai_name = $getAI->name;
$cn = $customers->get($getMessages->fetch()->id_customer);
$user_name = $cn->name;

if($_REQUEST['format'] == "txt"){
	// Cria um arquivo temporário com o nome "conversation.txt"
	$tempPath = tempnam(sys_get_temp_dir(), 'chat_');
	$tempFile = fopen($tempPath, 'w');

	// Escreve a data e hora atuais no arquivo
	$currentDateTime = date("Y-m-d H:i:s");
	fwrite($tempFile, "{$currentDateTime}\n\n");

	foreach ($getMessages as $showMessages) {
        $showMessages->content = removeCustomInput($showMessages->content);
	    if ($showMessages->role != 'system') {
	        // Escreve a mensagem no arquivo temporário com o nome personalizado
	        $name = $showMessages->role == 'assistant' ? $ai_name : $user_name;
	        fwrite($tempFile, "{$name}: {$showMessages->content}\n\n");
	    }
	}

	// Fecha o arquivo temporário
	fclose($tempFile);

	// Força o download do arquivo
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename="chat.txt"');
	header('Content-Length: ' . filesize($tempPath));

	// Lê e exibe o conteúdo do arquivo temporário
	readfile($tempPath);

	// Remove o arquivo temporário
	unlink($tempPath);	
}

if($_REQUEST['format'] == "pdf"){

    // Cria uma nova instância do TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Configurações do PDF
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor($user_name);
    $pdf->SetHeaderData('', '', $lang['header_title_pdf']." - ".$ai_name.' & '.$user_name);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    $pdf->setFontSubsetting(true);
    $pdf->SetFont('cid0jp', '', 10, '', true);

    // Adiciona uma página ao PDF
    $pdf->AddPage();

    // Escreve a data e hora atuais no PDF
    $currentDateTime = date("Y-m-d H:i:s");
    $pdf->Write(0, $currentDateTime."\n\n", '', 0, 'L', true, 0, false, false, 0);

	foreach ($getMessages as $showMessages) {
        $showMessages->content = removeCustomInput($showMessages->content);
	    if ($showMessages->role != 'system') {
	        $name = $showMessages->role == 'assistant' ? $ai_name : $user_name;
	        $pdf->SetFont('cid0jp', 'B', 10);
	        $pdf->Write(0, $name.": ", '', 0, 'L', true, 0, false, false, 0);
	        $pdf->SetFont('cid0jp', '', 10);
	        $pdf->Write(0, $showMessages->content, '', 0, 'L', true, 0, false, false, 0);
	        $pdf->Write(0, $showMessages->created_at."\n\n", '', 0, 'L', true, 0, false, false, 0);
	    }
	}


    ob_end_clean();
    // Força o download do arquivo
    $pdf->Output('chat.pdf', 'D');
}
	

if ($_REQUEST['format'] == "docx") {
    // Inicializa o objeto PhpWord
    $phpWord = new PhpWord();

    // Adiciona uma nova seção ao documento
    $section = $phpWord->addSection();

    // Cria um estilo de fonte personalizado para a data/hora inicial
    $phpWord->addFontStyle('dateTimeStyle', array('bold' => true));

    // Escreve a data e hora atuais no documento
    $currentDateTime = date("Y-m-d H:i:s");
    $section->addText($currentDateTime, 'dateTimeStyle');
    $section->addTextBreak();  

    // Escreve as mensagens no documento
    foreach ($getMessages as $showMessages) {
        $showMessages->content = removeCustomInput($showMessages->content);
        if ($showMessages->role != 'system') {
            $name = $showMessages->role == 'assistant' ? $ai_name : $user_name;
            $section->addText($name . ": " . $showMessages->content);
            $section->addText($showMessages->created_at);
            $section->addTextBreak();  // Adiciona um espaço em branco (quebra de linha)
        }
    }

    // Salva o documento como um arquivo temporário
    $tempPath = tempnam(sys_get_temp_dir(), 'chat_') . '.docx';
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    $objWriter->save($tempPath);

    // Força o download do arquivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="chat.docx"');
    header('Content-Length: ' . filesize($tempPath));

    // Lê e exibe o conteúdo do arquivo temporário
    readfile($tempPath);

    // Remove o arquivo temporário
    unlink($tempPath);
}