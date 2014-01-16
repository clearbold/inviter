inviter
=======

Inviter is a PHP app that generates emails with iCal (.ics) attachments to send meeting invites. It supports HTML email templates so you can branded emails.

I built inviter for two reasons:

* I don't have a calendar hooked up to my email, so I don't have a way to send invites to others that they can add to their calendars.

* I've been using GoToMeeting for our meetings and it doesn't send out invites to recipients like other apps do. It gives you a message to copy and paste, which is nice, but then recipients don't add those to their calendars.

With inviter, I can paste in the meeting message, set the date and time, enter multiple recipients, and a multipart email is sent with the invite as an attachment.

I'm just running it in my localhost and hitting the site when I need to use it.

### Using inviter

You'll need to make sure that the `app/tmp` directory is writeable. The `app/templates` directory should be readable.

Please do not use the included email.html template with the Clearbold design. Note that there are two variables in that template -- `<<intro>>` and `<<message>>` that get replaced in `send.php`.

You'll need to rename `app/config/example.config.php` to `config.php`.