<?php

require_once('config/config.php');
require_once('swiftmailer/lib/swift_required.php');

echo "To: " . $_POST['recipients'] . "<br />";

echo '<textarea rows="30" cols=100">';
echo "From: " . $_POST['sender_name'] . "(" . $_POST['sender'] . ")" . "\n";
echo "Subject: " . $_POST['subject'] . "\n\n";

echo "***" . "\n";
echo "Date: " . $_POST['date'] . "\n";
echo "Start Time: " . $_POST['start_time'] . "\n";
echo "End Time: " . $_POST['end_time'] . "\n";
echo "***" . "\n\n";

echo utf8_decode($_POST['message']);
echo '</textarea>';

echo "<br />" . strtotime($_POST['date'] . " " . $_POST['start_time']);

date_default_timezone_set($default_timezone);

$start_time = gmdate("Ymd",strtotime($_POST['date'] . " " . $_POST['start_time']))."T".gmdate("His",strtotime($_POST['date'] . " " . $_POST['start_time']))."Z";
$end_time = gmdate("Ymd",strtotime($_POST['date'] . " " . $_POST['end_time']))."T".gmdate("His",strtotime($_POST['date'] . " " . $_POST['end_time']))."Z";
$timestamp = gmdate("Ymd")."T".gmdate("His")."Z";
echo "<br />" . $start_time;
echo "<br />" . $end_time;
echo "<br />" . $timestamp;
$message = utf8_decode($_POST['message']);
$sender = $_POST['sender'];
$sender_name = $_POST['sender_name'];
$subject = $_POST['subject'];

$recipients = "";
$arr_recipients = explode(",", $_POST['recipients']);
foreach ($arr_recipients as $recipient) {
    $email_recipients[] = trim($recipient);
    $recipients .= "ATTENDEE:MAILTO:" . trim($recipient) . "\n";
}
$email_recipients[] = $sender;
$recipients = substr($recipients, 0, -1);
$uid = $timestamp . "_clearbold";

echo "<br /><br />" . strpos($message,"\r\n") . "<br />";

$message_email = '';
$forbidden = array(174,194);
for($i=0;$i<strlen($message);$i++){
    if(in_array(ord($message[$i]),$forbidden)) continue;
    else $message_email.= $message[$i];
    //echo ord($string[$i]).",";
}

$message_ics = str_replace("\r\n", '\n', $message);

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

$file = fopen("tmp/invite.ics","w");
fwrite($file, $ics);
fclose($file);

echo '<br /><textarea rows="30" cols="100">' . $ics . '</textarea>';

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

$transport = Swift_SmtpTransport::newInstance($smtp_server, $smtp_port)
  ->setUsername($smtp_username)
  ->setPassword($smtp_password)
  ;

$mailer = Swift_Mailer::newInstance($transport);

$result = $mailer->send($message);