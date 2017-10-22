<?php
    use SBLib\Installer\Validator;

    require('../../vendor/autoload.php');

    $Validator = new Validator(filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING));
    $Validator->validateAdmin()
        ->validateDatabase()
        ->validateForum();

    echo json_encode(array(
        'errors' => $Validator->getErrors(),
        'success' => $Validator->getSuccess()
    ));