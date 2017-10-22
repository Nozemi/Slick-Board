<?php

use SBLib\Database\DBUtil;
use SBLib\ThemeEngine\MainEngine;
use SBLib\Utilities\Config;

require('inc/globals.php');

    $Config = new Config;
    $dbUtil = new DBUtil((object) [
        'name' => $Config->get('dbName'),
        'user' => $Config->get('dbUser'),
        'pass' => $Config->get('dbPass'),
        'prefix' => $Config->get('dbPrefix')
    ]);
    $TemplateEngine = new MainEngine($dbUtil);

    echo $TemplateEngine->getTemplate('portal', 'portal');