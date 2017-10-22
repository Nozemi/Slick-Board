<?php
    use SBLib\Utilities\MISC;

    /*
     * Let's define findAutoloader if it doesn't already exist.
     * This method will help us automatically finding the autoloader.php from composer.
     */
    if(!function_exists('findAutoloader')) {
        function findAutoloader() {
            $autoLoader = 'vendor/autoload.php';
            if(!file_exists($autoLoader)) {
                for($i = 0; $i < 5; $i++) {
                    if(!file_exists(($autoLoader))) {
                        $autoLoader = '../' . $autoLoader;
                    } else {
                        return $autoLoader;
                    }
                }
            } else {
                return $autoLoader;
            }

            return false;
        }
    }

    // Considering the whole forum software revolves around the Composer package nozemi/forumlib,
    // we'll be killing the script, unless it actually finds the autoload.php from composer.
    if(!findAutoloader()) {
        // TODO: Add logging to a log file.
        die('Are you sure Composer is installed correctly? Autoloader was not found.');
    }

    require(findAutoloader());

    $rootDirectory = MISC::getRootDirectory();