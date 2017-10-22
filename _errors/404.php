<?php
    use SBLib\ThemeEngine\MainEngine;

    require('../inc/globals.php');

    if(isset($_COOKIE['themeName'])) {
        $TE = new MainEngine($_COOKIE['themeName'], $sbSql, $sbConfig);
    } else {
        $TE = new MainEngine($sbConfig->getConfigValue('theme'), $sbSql, $sbConfig);
    }

    echo $TE->getTemplate('404', '_errors');