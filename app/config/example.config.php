<?php
$debug = False; // Set to True to review output; False redirects back to invite form

$send_html = False; // Set to True to use the HTML template and send multipart

$default_timezone = 'America/New_York'; // http://us2.php.net/manual/en/timezones.america.php

$default_sender = 'you@yourdomain.com'; // Prepopulates the form
$default_sender_name = 'Your Name'; // Prepopulates the form

$smtp_server = ''; // I use https://mandrillapp.com for these
$smtp_port = '587';
$smtp_username = '';
$smtp_password = '';