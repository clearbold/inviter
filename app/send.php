<?php

require_once('config/config.php');
require_once('swiftmailer/lib/swift_required.php');

// Set the default timezone for date functions from the config file
date_default_timezone_set($default_timezone);

// Set start time to GMT, formatted as iCal expects
$start_time = gmdate("Ymd",strtotime($_POST['date'] . " " . $_POST['start_time']))."T".gmdate("His",strtotime($_POST['date'] . " " . $_POST['start_time']))."Z";
$end_time = gmdate("Ymd",strtotime($_POST['date'] . " " . $_POST['end_time']))."T".gmdate("His",strtotime($_POST['date'] . " " . $_POST['end_time']))."Z";
// Format the current timestamp to GMT as iCal expects
$timestamp = gmdate("Ymd")."T".gmdate("His")."Z";
$uid = $timestamp . "_clearbold";

$sender = $_POST['sender'];
$sender_name = $_POST['sender_name'];
$subject = $_POST['subject'];

// This gets some broken/hidden characters out of the pasted in message
// Example message was pasted from GoToMeeting app
$message = utf8_decode($_POST['message']);
$message_email = '';
// When $message goes into plaintext email, R symbols break, this strips them and one other out
$forbidden = array(174,194);
for($i=0;$i<strlen($message);$i++){
    if(in_array(ord($message[$i]),$forbidden)) continue;
    else $message_email.= $message[$i];
}
// R symbols work in the iCal attachment (tested in Apple's iCal), but \r\n breaks the Description 'field'
// \n chars on their own work in iCal... Haven't tested Outlook
$message_ics = str_replace("\r\n", '\n', $message);
$message_ics = str_replace(chr(174), "(R)", $message_ics); // There will be more of these
$message_html = nl2br($message);

// Build up the ATTENDEE recipients list in the iCal attachment, and build up the clean array for the email
$recipients = "";
$arr_recipients = explode(",", $_POST['recipients']);
foreach ($arr_recipients as $recipient) {
    $email_recipients[] = trim($recipient);
    $recipients .= "ATTENDEE:MAILTO:" . trim($recipient) . "\n";
}
$email_recipients[] = $sender;
$recipients = substr($recipients, 0, -1);

// Here's the iCal attachment
$ics = <<<ICS
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Clearbold LLC//Clearbold Inviter//EN
METHOD:REQUEST
BEGIN:VEVENT
SUMMARY:$subject
DTSTART;VALUE=DATE-TIME:$start_time
DTEND;VALUE=DATE-TIME:$end_time
DTSTAMP;VALUE=DATE-TIME:$timestamp
UID:$uid
SEQUENCE:3
ATTENDEE:MAILTO:$sender
$recipients
CREATED;VALUE=DATE-TIME:$timestamp
DESCRIPTION:$message_ics
LAST-MODIFIED;VALUE=DATE-TIME:$timestamp
LOCATION:
ORGANIZER:MAILTO:$sender
PRIORITY:5
STATUS:CONFIRMED
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR
ICS;

// Write it to our tmp directory
$file = fopen("tmp/invite.ics","w");
fwrite($file, $ics);
fclose($file);

// HTML email
if ($send_html) {
    $email_template = fopen("templates/email.html","r");
    $email_html = fread($email_template, filesize("templates/email.html"));
    fclose($email_template);
    $email_html = str_replace("<<message>>", $message_html, $email_html);
    $email_html = str_replace(chr(174), "&reg;", $email_html); // There will be more of these...
    $email_tmp = fopen("tmp/email.html","w");
    fwrite($email_tmp, $email_html);
    fclose($email_tmp);
}

// Here's the email
// Create the message
$message = Swift_Message::newInstance()

  // Give the message a subject
  ->setSubject($subject)

  // Set the From address with an associative array
  ->setFrom(array($sender => $sender_name))

  // Set the To addresses with an associative array
  ->setTo($email_recipients)

  // Give it a body
  ->setBody($message_email)

  // And optionally an alternative body
  //->addPart('<q>Here is the message itself</q>', 'text/html')

  // Optionally add any attachments
  ->attach(Swift_Attachment::fromPath('tmp/invite.ics'))
  ;

  if ($send_html)
    $message->addPart($email_html, 'text/html');

$transport = Swift_SmtpTransport::newInstance($smtp_server, $smtp_port)
  ->setUsername($smtp_username)
  ->setPassword($smtp_password)
  ;

$mailer = Swift_Mailer::newInstance($transport);

if (!$debug) {
    $result = $mailer->send($message);
    header('Location: /');
} else {
?><!--[if i]><![endif]--><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <!--[if gte IE 9]><!-->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="../ui/css/styles.css" />
    <!-- <!--[endif]-->

    <!-- Windows 8 / RT -->
    <meta http-equiv="cleartype" content="on" accept-charset="utf-8" />

    <link type="text/plain" rel="author" href="Mark J. Reeves / Clearbold" />
    <title>Inviter</title>
</head>
<body>
    <div class="wrapper">
        <textarea rows="40" readonly>
Sender: <?= $sender ?>

Sender Name: <?= $sender_name ?>

Subject: <?= $subject ?>

Recipients: <? $i=1; foreach ($email_recipients as $recipient) { ?><?= $recipient ?><? if ($i<count($email_recipients)) echo ','; $i++; ?><? } ?>


***

<?= $message_email ?></textarea><br /><br />
        <textarea rows="40" readonly><?= $ics ?></textarea><br /><br />
        <?
        if ($send_html) { ?>
        <iframe src="tmp/email.html"></iframe>
        <? } ?>
    </div>
</body>
</html>
<?php
}

