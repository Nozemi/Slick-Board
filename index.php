<?php
use SBLib\ThemeEngine\MainEngine;

use SBLib\Utilities\MISC;

use SBLib\Users\User;

use SBLib\Forums\Category;
use SBLib\Forums\Topic;
use SBLib\Forums\Thread;

require('inc/globals.php');

$dir = MISC::findFile('plugins'); // Gets the library directory's actual position.

// Gets the classes inside the library directory.
foreach(glob($dir . '/*.php') as $file) {
    require_once($file);
}
foreach(glob($dir . '/*/*.php') as $file) {
    require_once($file);
}

if(isset($_COOKIE['themeName'])) {
    $TE = new MainEngine($_COOKIE['themeName'], $sbSql, $sbConfig);
} else {
    $TE = new MainEngine(MISC::findKey('theme', $sbConfig->config), $sbSql, $sbConfig);
}

if(isset($_COOKIE['devkey']) != 'g4r39poiuhtyo8934hrgo8it5h907gh3tg357gpgh7r3458') {
    $nope = $TE->getTemplate('coming_soon', 'misc');
    if(!$nope) {
        echo $TE->getTemplate('404', 'errors');
    } else {
        echo $nope;
    }

    exit;

}

if(!isset($_GET['page'])) {
    $_GET['page'] = 'portal';
}

if(empty($TE->getConfig())) {
    if(empty($_SESSION['user']) && (
            $_GET['page'] == 'settings' || $_GET['page'] == 'signout'
        )) {
        echo $TE->getTemplate('notloggedin', 'errors');
    }

    if(!empty($_SESSION['user']) && (
            $_GET['page'] == 'login' || $_GET['page'] == 'register' ||
            $_GET['page'] == 'signin' || $_GET['page'] == 'signup'
        )) {
        echo $TE->getTemplate('loggedin', 'errors');
    }
} else {
    if(empty($_SESSION['user']) && in_array($_GET['page'], $TE->getConfig()['loginRequired'])) {
        echo $TE->getTemplate('notloggedin', 'errors');
        exit;
    }

    if(!empty($_SESSION['user']) && in_array($_GET['page'], $TE->getConfig()['loginDeny'])) {
        echo $TE->getTemplate('loggedin', 'errors');
        exit;
    }
}

if($_GET['page'] == 'signout') {
    unset($_SESSION['user']);
    header("Location: /portal");
}

$U = new User($sbSql);

if(($_GET['page'] == 'profile' && !isset($_GET['username']))
    || $_GET['page'] == 'profile' && !$U->usernameExists($_GET['username'])) {
    echo $TE->getTemplate('profile_not_found', 'user'); exit;
}

if(isset($_GET['category'], $_GET['topic'], $_GET['thread']) && $_GET['page'] == 'forums') {

    $C = new Category($sbSql);
    $cat = $C->getCategory($_GET['category'], false);

    $T = new Topic($sbSql);
    $top = $T->getTopic($_GET['topic'], false, $cat->id);

    $TR = new Thread($sbSql);
    if(isset($_GET['threadId'])) {
        $trd = $TR->getThread($_GET['threadId']);
    } else {
        $trd = $TR->getThread($_GET['thread'], false, $top->id);
    }
    $trd->setPosts();

    if(empty($trd->title)) {
        $html = $TE->getTemplate('notfound','forums');
    } else {
        $html = $TE->getTemplate('thread', 'forums');
    }
} else if(isset($_GET['category'], $_GET['topic']) && $_GET['page'] == 'forums') {

    $C = new Category($sbSql);
    $cat = $C->getCategory($_GET['category'], false);

    $T = new Topic($sbSql);
    $top = $T->getTopic($_GET['topic'], false, $cat->id);

    if(empty($top->title)) {
        $html = $TE->getTemplate('notfound','forums');
    } else {
        $html = $TE->getTemplate('threads', 'forums');
    }
} else if(isset($_GET['category']) && $_GET['page'] == 'forums') {

    $C = new Category($sbSql);
    $cat = $C->getCategory($_GET['category'], false);

    if(empty($cat->title)) {
        $html = $TE->getTemplate('notfound','forums');
    } else {
        $html = $TE->getTemplate('category', 'forums');
    }
} else if($_GET['page'] == 'forums') {
    $html = $TE->getTemplate('categories', 'forums');
} else {
    $html = $TE->getTemplate($_GET['page']);
}

if(empty($html)) {
    echo $TE->getTemplate('404', 'errors');
} else {
    echo $html;
}