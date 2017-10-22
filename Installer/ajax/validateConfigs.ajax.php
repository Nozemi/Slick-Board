<?php
    use SBLib\Handlers\Install\Validator;

    require('../../inc/globals.php');

    $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $Validator = new Validator($data);
    $Validator->validateAdmin()
        ->validateDatabase()
        ->validateForum();

    echo json_encode(array(
        'errors' => $Validator->getErrors(),
        'success' => $Validator->getSuccess()
    ));