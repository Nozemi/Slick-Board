<?php
    use SBLib\Database\DBUtil;
    use SBLib\Database\DBUtilException;

    require('../../vendor/autoload.php');

    $errors = array();
    $success = array(
        'forumName' => true,
        'forumDescription' => true,
        'contactEmail' => true,
        'rootDirectory' => true,
        'dbName' => true,
        'adminEmail' => true,
        'adminUsername' => true,
        'adminPassword' => true,
        'dbHost' => ['status' => true, 'message' => 'Will use default host. (<i>localhost</i>)'],
        'dbPort' => ['status' => true, 'message' => 'Will use default port. (<i>3306</i>)'],
        'dbPrefix' => ['status' => true, 'message' => 'Won\'t use any prefix.'],
        'dbUser' => ['status' => true, 'message' => 'Will use default username. (<i>root</i>)'],
        'dbPass' => ['status' => true, 'message' => 'Will use empty password as default.'],
        'forumTheme' => ['status' => true, 'message' => 'Will use default theme. (<i>Slickboard</i>)'],
        'forumLanguage' => ['status' => true, 'message' => 'Will use default language. (<i>English</i>)'],
        'forumIntegration' => ['status' => true, 'message' => 'Will use default integration. (<i>SBLibIntegration</i>)']
    );

    $data = (object) $_POST;

    if(!$data->forumName) {
        $errors['forumName'] = 'Forum name isn\'t set.';
        $success['forumName'] = false;
    }

    if(!$data->forumDescription) {
        $errors['forumDescription'] = 'Forum description isn\'t set.';
        $success['forumDescription'] = false;
    }

    if(!$data->contactEmail) {
        $errors['contactEmail'] = 'Contact email isn\'t set.';
        $success['contactEmail'] = false;
    }

    if(!$data->rootDirectory) {
        $errors['rootDirectory'] = 'Root directory isn\'t set.';
        $success['rootDirectory'] = false;
    } else if($data->rootDirectory) {
        if(!file_exists($data->rootDirectory . '/index.php')) {
            $errors[] = '<strong>[Root Directory]</strong> Are you sure the root directory is correct? Could not find the index.php file for the forums in there. (Path: ' . $data->rootDirectory . ')';
        }

        if(!is_writeable($data->rootDirectory)) {
            $errors[] = '<strong>[Root Directory]</strong> The root directory isn\'t writeable. Please make sure the webserver is allowed to write files in there. (Path: ' . $data->rootDirectory . ')';
        }

        $errors['rootDirectory'] = 'Reference errors listed at top of the page, prefixing Root Directory.';
        $success['rootDirectory'] = false;
    }

    if(!$data->dbName) {
        $errors['dbName'] = 'Database name is required in order to install the forum.';
        $success['dbName'] = false;
    } else if($data->dbName) {
        try {
            $db = new DBUtil((object) array(
                'host'      => $data->dbHost,
                'port'      => $data->dbPort,
                'name'      => $data->dbName,
                'user'      => $data->dbUser,
                'pass'      => $data->dbPass,
                'prefix'    => $data->dbPrefix
            ));

            if(!$db->isInitialized()) {
                $errors[] = 'Database is not initialized. (' . $db->getLastError() . ')';
            }
        } catch(Exception $exception) {
            $errors[] = 'Unable to connect to database. (' . $exception->getMessage() . ')';
        }
    }

    if(!$data->adminEmail) {
        $errors['adminEmail'] = 'Email for admin account is required.';
        $success['adminEmail'] = false;
    } else if($data->adminEmail) {
        if(!filter_var($data->adminEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['adminEmail'] = 'Email is invalid. Please use the correct format. (<i>name@provider.ext</i>)';
            $success['adminEmail'] = false;
        }
    }

    if(!$data->adminUsername) {
        $errors['adminUsername'] = 'Username for admin account is required.';
        $success['adminUsername'] = false;
    }

    if(!$data->adminPassword) {
        $errors['adminPassword'] = 'Password for admin account is required.';
        $success['adminPassword'] = false;
    }

    echo json_encode(array(
        'errors' => $errors,
        'success' => $success
    ));