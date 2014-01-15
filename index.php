<!--[if i]><![endif]--><!DOCTYPE html>
<?php
require_once('app/config/config.php');
?>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <!--[if gte IE 9]><!-->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="ui/css/styles.css" />
    <!-- <!--[endif]-->

    <!-- Windows 8 / RT -->
    <meta http-equiv="cleartype" content="on" />

    <link type="text/plain" rel="author" href="Mark J. Reeves / Clearbold" />
    <title>Inviter</title>
</head>
<body>
    <div class="wrapper">
        <form>
            <p>
                <label>Sender (Email):</label>
                <input name="sender" type="email" value="<?= $default_sender ?>" />
            </p>
            <p>
                <label>Recipient(s) (Email):</label>
                <textarea name="recipients"></textarea>
            </p>
        </form>
    </div>
</body>
</html>