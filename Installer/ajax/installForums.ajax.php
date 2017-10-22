<?php

    use SBLib\Database\DBUtil;
    use SBLib\Handlers\Install\Installer;

    require('../../inc/globals.php');

    $data = (object) filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

    $data->dbUser = $data->dbName = 'slickboard';
    $data->dbPass = 'uA9p4M_BV(%9wTEW';

    $dbUtil = new DBUtil((object) [
        'name'      => $data->dbName,
        'user'      => $data->dbUser,
        'pass'      => $data->dbPass
    ]);

    $data->adminUsername = 'Nozemi';
    $data->adminEmail = 'nozemi95@gmail.com';
    $data->adminPassword = 'test123';

    $data->forumName = 'Slick Board';

    new Installer($dbUtil, $data);