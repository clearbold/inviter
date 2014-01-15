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
    <meta http-equiv="cleartype" content="on" accept-charset="utf-8" />

    <link type="text/plain" rel="author" href="Mark J. Reeves / Clearbold" />
    <title>Inviter</title>
</head>
<body>
    <div class="wrapper">
        <form method="POST" action="app/send.php">
            <p>
                <label>Sender (Name):</label>
                <input name="sender_name" type="text" value="<?= $default_sender_name ?>" />
            </p>
            <p>
                <label>Sender (Email):</label>
                <input name="sender" type="email" value="<?= $default_sender ?>" />
            </p>
            <p>
                <label>Recipient(s) (Email):</label>
                <textarea name="recipients" rows="4"></textarea>
            </p>
            <p>
                <label>Subject:</label>
                <input name="subject" type="text" value="" />
            </p>
            <p>
                <label>Date (mm/dd/yyyy):</label>
                <input name="date" type="text" value="" />
            </p>
            <p>
                <label>Start Time (hh:mm):</label>
                <input name="start_time" type="text" value="" />
            </p>
            <p>
                <label>End Time (hh:mm):</label>
                <input name="end_time" type="text" value="" />
            </p>
            <p>
                <label>Message:</label>
                <textarea name="message" rows="20"></textarea>
            </p>
            <p>
                <input type="submit" name="submit" value="Send" />
            </p>
        </form>
    </div>
</body>
</html>