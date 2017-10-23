<?php
    use SBLib\ThemeEngine\MainEngine;

    require('inc/globals.php');

    $TemplateEngine = new MainEngine;
    echo $TemplateEngine->getTemplate('portal', 'portal');