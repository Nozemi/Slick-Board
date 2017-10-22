<?php
    use SBLib\Handlers\Install\Installer;

    require('../../inc/globals.php');

    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    if(empty($data)) {
        die("Sorry, you need to provide some data.");
    } else {
        $data = (object) $data;
    }

    $Installer = new Installer($data);

    echo json_encode([
        'errors' => $Installer->getErrors(),
        'success' => $Installer->getSuccess()
    ]);