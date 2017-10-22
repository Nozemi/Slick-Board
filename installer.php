<?php
    require('inc/globals.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Slick Board Installer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="<?php echo $currentDirectory->client; ?>themes/slickboard/_assets/dist/slickboard.min.css" />
    <script type="text/javascript" src="<?php echo $currentDirectory->client; ?>themes/slickboard/_assets/dist/slickboard.min.js"></script>
</head>
<body>
<div id="banner">
    <div class="container">
        <img src="<?php echo $currentDirectory->client; ?>themes/slickboard/_assets/images/sb_header.png" />
    </div>
</div>

<br />

<div class="container">

    <div id="messages"></div>
    <form id="installerForm">
        <div class="table-responsive">
            <table class="table table-slickboard table-striped table-hover">
                <thead>
                <tr>
                    <th colspan="2">Slick Board - Installation</th>
                </tr>
                <tr class="width-def">
                    <th width="20%"></th>
                    <th width="80%"></th>
                </tr>
                </thead>
                <tbody>
                <!-- Confiugure Forum Details -->
                <tr class="header">
                    <td colspan="2">Forum Details</td>
                </tr>
                <tr class="digest">
                    <td colspan="2">Make your own name and description for the forum. You may choose whatever you feel like, this won't matter to the rest of the installation.</td>
                </tr>
                <tr>
                    <td>Forum Name:</td>
                    <td><input name="forumName" type="text" class="form-control" placeholder="My Awesome Forum"></td>
                </tr>
                <tr>
                    <td>Forum Description:</td>
                    <td><input name="forumDescription" type="text" class="form-control" placeholder="This is my awesome forum, with an awesome description."></td>
                </tr>
                <tr>
                    <td>Contact Email:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-btn"><button class="btn btn-info">?</button></span>
                            <input name="contactEmail" type="text" class="form-control" placeholder="admin@awesomeforum.my">
                        </div>
                    </td>
                </tr>

                <!-- Configure Forum Installation -->
                <tr class="header">
                    <td colspan="2">Forum Installation</td>
                </tr>
                <tr class="digest">
                    <td colspan="2">Details that needs to be known by the installer in order to set up the configuration properly.</td>
                </tr>
                <tr>
                    <td>Root Directory:</td>
                    <td><input name="rootDirectory" type="text" class="form-control" placeholder="/root/path/on/webserver/"></td>
                </tr>
                <tr>
                    <td>Theme:</td>
                    <td>
                        <select name="forumTheme" class="form-control">
                            <option>Choose Theme</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Language:</td>
                    <td>
                        <select name="forumLanguage" class="form-control">
                            <option>Choose Language</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Integration:</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-btn"><button class="btn btn-info">?</button></span>
                            <select name="forumIntegration" class="form-control">
                                <option>Choose Integration</option>
                            </select>
                        </div>
                    </td>
                </tr>

                <!-- Configure Database Details -->
                <tr class="header">
                    <td colspan="2">Database Details</td>
                </tr>
                <tr class="digest">
                    <td colspan="2">Insert your database details here. Make sure that you've already made the actual database. The installer will populate the databse
                        with tables and content, but it won't make the actual database for you.</td>
                </tr>
                <tr>
                    <td>Database Host:</td>
                    <td><input name="dbHost" type="text" class="form-control" placeholder="localhost"></td>
                </tr>
                <tr>
                    <td>Database Port:</td>
                    <td><input name="dbPort" type="text" class="form-control" placeholder="3306"></td>
                </tr>
                <tr>
                    <td>Database Name:</td>
                    <td><input name="dbName" type="text" class="form-control" placeholder="database_name"></td>
                </tr>
                <tr>
                    <td>Database Prefix:</td>
                    <td><input name="dbPrefix" type="text" class="form-control" placeholder="prefix_"></td>
                </tr>
                <tr>
                    <td>Database User:</td>
                    <td><input name="dbUser" type="text" class="form-control" placeholder="username"></td>
                </tr>
                <tr>
                    <td>Database Password:</td>
                    <td><input name="dbPass" type="password" class="form-control" placeholder="password"></td>
                </tr>

                <!-- Configure Admin Account -->
                <tr class="header">
                    <td colspan="2">Admin Account</td>
                </tr>
                <tr class="digest">
                    <td colspan="2">Make your new admin account, which is what you'll be using to log in and manage your freshly installed forum with.</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input name="adminEmail" type="text" class="form-control" placeholder="name@address.ext"></td>
                </tr>
                <tr>
                    <td>Username:</td>
                    <td><input name="adminUsername" type="text" class="form-control" placeholder="Username"></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input name="adminPassword" type="password" class="form-control" placeholder="Password"></td>
                </tr>
                <tr>
                    <td colspan="2"><hr></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="float-right">
                            <button class="btn btn-danger">Cancel</button>
                            <button class="btn btn-primary" onClick="validateSettings()">Validate</button>
                            <button class="btn btn-success" disabled>Install</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>
<script>
    $('#installerForm').submit(function(e) {
        e.preventDefault();
    });

    $errorMessage = $('<div class="invalid-feedback"></div>');
    $successMessage = $('<div class="valid-feedback"></div>');
    $errorAlert = $('<p class="alert alert-danger"></p>');

    function validateSettings() {
        $('.invalid-feedback').remove();
        $('.valid-feedback').remove();
        $('.alert').remove();

        var verificationAjax = new SBAjax($('#messages'), '<?php echo $currentDirectory->client; ?>Installer/ajax/validateConfigs.ajax.php', $('#installerForm').serialize(), 'json');
        verificationAjax.sendData($('#messages'), function(response, message_element) {
            $.each(response.success, function(key, message) {
                if(message === true) {
                    $('[name="' + key + '"]').addClass('is-valid').removeClass('is-invalid');
                } else if(message.status === true) {
                    $('[name="' + key + '"]').addClass('is-valid').removeClass('is-invalid').closest('td').append($successMessage.clone().html(message.message));
                }
            });

            $.each(response.errors, function(key, message) {
                if($.isNumeric(key)) {
                    $(message_element).append($errorAlert.clone().html(message));
                } else {
                    $('[name="' + key + '"]').addClass('is-invalid').removeClass('is-valid').closest('td').append($errorMessage.clone().html(message));
                }
            });
        });
    }
</script>
</body>
</html>
