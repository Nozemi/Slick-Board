<?php
    use SBLib\Utilities\MISC;

    require('../../inc/globals.php');

    if(MISC::findFile('install.lock')) {
        // TODO: Get siteRoot from config.
        header("Location: /");
    }

    $postData = (object) $_POST;
    $config = [
        'database' => [
            'dbUser'            => $postData->dbUser,
            'dbPass'            => $postData->dbPass,
            'dbHost'            => $postData->dbHost,
            'dbPort'            => $postData->dbPort,
            'dbName'            => $postData->dbName,
            'prefix'            => $postData->prefix
        ],
        'main'     => [
            'name'              => $postData->siteName,
            'description'       => $postData->description,
            'lang'              => $postData->language,
            'theme'             => $postData->theme,
            'captchaPublicKey'  => $postData->reCaptchaPublicKey,
            'captchaPrivateKey' => $postData->reCaptchaPrivateKey,
            'siteRoot'          => $postData->siteRoot
            // TODO: Add slickboard group
            // TODO: Add slickboard news forum
        ]
    ];

    foreach($config as $name => $values) {
        $values = json_encode($values);

        // TODO: Write config file with $name and $values to the config directory.
    }