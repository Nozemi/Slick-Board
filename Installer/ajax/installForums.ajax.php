<?php
    use SBLib\Utilities\MISC;
    use SBLib\Database\DBUtilQuery;
    use SBLib\Users\User;
    use SBLib\Users\Group;
    use SBLib\Users\Permissions;
    use SBLib\Forums\Category;
    use SBLib\Forums\Topic;
    use SBLib\Forums\Thread;
    use SBLib\Forums\Post;

    require('../../inc/globals.php');

    if(MISC::findFile('install.lock')) {
        // TODO: Get siteRoot from config.
        header("Location: /");
    }

    $data = (object) $_POST;

    $usersTable = $groupsTable = $categoriesTable = $contentStringsTable = $permissionsTable = $postsTable = $threadsTable = $topicsTable = $usersSessionTable = new DBUtilQuery();

    $queries = array(
        $usersTable->setName('usersTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}users` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `username` varchar(255) DEFAULT NULL,
                    `password` varchar(255) DEFAULT NULL,
                    `email` varchar(255) DEFAULT NULL,
                    `avatar` varchar(255) DEFAULT '{{theme::imgdir}}user/avatar.jpg',
                    `group` int(11) DEFAULT NULL,
                    `regip` varchar(255) DEFAULT NULL,
                    `lastip` varchar(255) DEFAULT NULL,
                    `regdate` datetime DEFAULT NULL,
                    `lastlogindate` datetime DEFAULT NULL,
                    `firstname` varchar(255) DEFAULT NULL,
                    `lastname` varchar(255) DEFAULT NULL,
                    `about` longtext,
                    `location` varchar(45) DEFAULT NULL,
                    `discordId` varchar(45) DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `username_UNIQUE` (`username`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
            "),

        $usersSessionTable->setName('usersSessionTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}users_session` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `uid` int(11) DEFAULT NULL,
                    `lastActive` datetime DEFAULT NULL,
                    `ipAddress` varchar(255) DEFAULT NULL,
                    `created` datetime DEFAULT NULL,
                    `lastPage` varchar(255) DEFAULT NULL,
                    `phpSessId` varchar(255) DEFAULT NULL,
                    `userAgent` longtext,
                PRIMARY KEY (`id`),
                UNIQUE KEY `phpSessId_UNIQUE` (`phpSessId`)
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
            "),

        $groupsTable->setName('groupsTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}groups` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `title` varchar(255) DEFAULT NULL,
                    `desc` varchar(255) DEFAULT NULL,
                    `order` int(2) DEFAULT NULL,
                    `admin` tinyint(1) DEFAULT '0',
                    `discordId` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
            "),

        $categoriesTable->setName('categoriesTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}categories` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `title` varchar(255) DEFAULT NULL,
                    `description` varchar(255) DEFAULT NULL,
                    `order` int(2) DEFAULT '0',
                    `enabled` tinyint(1) DEFAULT '1',
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
            "),

        $contentStringsTable->setName('contentStringsTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}content_strings` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `key` varchar(45) DEFAULT NULL,
                    `value` longtext,
                PRIMARY KEY (`id`),
                UNIQUE KEY `key_UNIQUE` (`key`)
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
            "),

        $permissionsTable->setName('permissionsTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}permissions` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `groupId` int(11) DEFAULT NULL,
                    `userId` int(11) DEFAULT NULL,
                    `categoryId` int(11) DEFAULT NULL,
                    `topicId` int(11) DEFAULT NULL,
                    `threadId` int(11) DEFAULT NULL,
                    `read` tinyint(4) DEFAULT NULL,
                    `post` tinyint(4) DEFAULT NULL,
                    `mod` tinyint(4) DEFAULT NULL,
                    `admin` tinyint(4) DEFAULT NULL,
                    `reply` tinyint(4) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
            "),

        $postsTable->setName('postsTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}posts` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `post_content_html` longtext,
                    `post_content_text` longtext,
                    `authorId` int(11) DEFAULT NULL,
                    `threadId` int(11) DEFAULT NULL,
                    `postDate` datetime DEFAULT NULL,
                    `editDate` datetime DEFAULT NULL,
                    `originalPost` tinyint(1) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

            "),

        $threadsTable->setName('threadsTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}threads` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `title` varchar(255) DEFAULT NULL,
                    `topicId` int(11) DEFAULT NULL,
                    `authorId` int(11) DEFAULT NULL,
                    `dateCreated` datetime DEFAULT NULL,
                    `lastEdited` datetime DEFAULT NULL,
                    `sticky` tinyint(1) DEFAULT NULL,
                    `closed` tinyint(1) DEFAULT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
            "),

        $topicsTable->setName('topicsTable')
            ->setQuery("
                CREATE TABLE `{{DBP}}topics` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `categoryId` int(11) DEFAULT NULL,
                    `title` varchar(255) DEFAULT NULL,
                    `description` varchar(255) DEFAULT NULL,
                    `icon` varchar(255) DEFAULT NULL,
                    `enabled` tinyint(1) DEFAULT '1',
                    `order` int(2) DEFAULT '0',
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
            ")
    );

    $sbSql->addQueries($queries);
    $sbSql->runQueries();

    $adminGroup = new Group($sbSql);
    $adminGroup->setName('Administrator')
        ->setAdmin(true)
        ->setDescription('Forum administrators')
        ->createGroup();

    $defaultGroup = new Group($sbSql);
    $defaultGroup->setName('Member')
        ->setAdmin(false)
        ->setDescription('Default forum member group')
        ->createGroup();

    $adminUser = new User($sbSql);
    $adminUser->setUsername($data->adminUsername)
        ->setEmail($data->adminEmail)
        ->setPassword($data->adminPassword, $data->adminPassword)
        ->setGroup($adminGroup->getId())
        ->register();

    $firstCategory = new Category($sbSql);
    $firstCategory->setEnabled(true)
        ->setTitle('First Category')
        ->setDescription('This is the first category, automagically generated by the forum installer.')
        ->createCategory();

    $firstTopic = new Topic($sbSql);
    $firstTopic->setEnabled(true)
        ->setTitle('First Topic')
        ->setDescription('This is the first topic, which also is automagically generated by the forum installer.')
        ->setCategoryId($firstCategory->getId())
        ->createTopic();

    $firstPost = new Post($sbSql);
    $firstPost->setAuthor($adminUser->getId())
        ->setHTML("
            <h1>Welcome to {$data->forumName}!</h1>
            
            <p>We've recently installed the forum, and this is some automagically generated content.
            The content was generated by the forum installer. It can however be deleted.</p>
            
            <p>If you're a visitor, you're probably a bit early on in your adventure here. Please
            stay tuned while the forum is configured and set up.</p>
            
            <p>Kind Regards,<br />
            Administrator, {$adminUser->username}</p>
        ");

    $firstThread = new Thread($sbSql);
    $firstThread->setTitle("Welcome to {$data->forumName}!")
        ->setAuthor($adminUser->getId())
        ->setTopicId($firstTopic->getId())
        ->createThread(null, $firstPost);

    $config = [
        'database' => [
            'dbUser'            => $data->dbUser,
            'dbPass'            => $data->dbPass,
            'dbHost'            => $data->dbHost,
            'dbPort'            => $data->dbPort,
            'dbName'            => $data->dbName,
            'prefix'            => $data->prefix
        ],
        'main'     => [
            'name'              => $data->siteName,
            'description'       => $data->description,
            'lang'              => $data->language,
            'theme'             => $data->theme,
            'captchaPublicKey'  => $data->reCaptchaPublicKey,
            'captchaPrivateKey' => $data->reCaptchaPrivateKey,
            'siteRoot'          => $data->siteRoot,
            'defaultGroup'      => $defaultGroup->getId(),
            'newsForum'         => $firstTopic->getId()
        ]
    ];

    foreach($config as $name => $values) {
        // TODO: Generate the config files.
        $options = json_encode($values);
        $configFile = fopen($data->rootDirectory . '/config/' . $name . '.conf.json', 'w');
        fwrite($configFile, $options);
        fclose($configFile);
    }

    // Write install.lock
    $installLock = fopen($data->rootDirectory . '/Installer/install.lock', 'w');
    fwrite($installLock,'Locked installation');
    fclose($installLock);

