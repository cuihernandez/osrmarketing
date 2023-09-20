<?php
function renderLanguageSection($title, $moduleName, $buttonText) {
    echo '
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">' . $title . '</h1>';

    if (!empty($buttonText)) {
        echo '
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="'.@$base_url.'/admin/'.$moduleName.'/add" class="btn btn-success btn-primary"><i class="bi bi-plus-circle"></i> ' . $buttonText . '</a>
        </div>';
    }

    echo '
    </div>
    ';
}

?>