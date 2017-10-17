<?php
    use SBLib\Database\DBUtil;
    use SBLib\Database\DBUtilException;

    require('../../vendor/autoload.php');

    $errors = array();
    $data = (object) $_POST;

    if(!$data->forumName) {
        $errors['forumName'] = 'Forum name isn\'t set.';
    }

    if(!$data->forumDescription) {
        $errors['forumDescription'] = 'Forum description isn\'t set.';
    }

    if(!$data->contactEmail) {
        $errors['contactEmail'] = 'Contact email isn\'t set.';
    }

    if(!$data->rootDirectory) {
        $errors['rootDirectory'] = 'Root directory isn\'t set.';
    } else if($data->rootDirectory) {
        if(!file_exists($data->rootDirectory . '/index.php')) {
            $errors[] = 'Are you sure the root directory is correct? Could not find the index.php file for the forums in there. (Path: ' . $data->rootDirectory . ')';
        }

        if(!is_writable($data->rootDirectory)) {
            $errors[] = 'The root directory isn\'t writeable. Please make sure the webserver is allowed to write files in there. (Path: ' . $data->rootDirectory . ')';
        }
    }

    if(!$data->dbName) {
        $errors['dbName'] = 'Database name is required in order to install the forum.';
    }

    try {
        $db = new DBUtil((object) array(
            'host'      => $data->dbHost,
            'port'      => $data->dbPort,
            'name'      => $data->dbName,
            'user'      => $data->dbUser,
            'pass'      => $data->dbPass,
            'prefix'    => $data->dbPrefix
        ));
    } catch(DBUtilException $exception) {
        $errors[] = 'Unable to connect to database. (' . $exception->getMessage() . ')';
    }

    if(!$data->adminEmail) {
        $errors['adminEmail'] = 'Email for admin account is required.';
    }

    if(!$data->adminUsername) {
        $errors['adminUsername'] = 'Username for admin account is required.';
    }

    if(!$data->adminPassword) {
        $errors['adminPassword'] = 'Password for admin account is required.';
    }

    echo json_encode($errors);