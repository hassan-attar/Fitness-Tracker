<?php
  require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/vendor/autoload.php";
  require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/config.php";

  use Mailgun\Mailgun;
  function send_email($to, $subject, $text) {
    // First, instantiate the SDK with your API credentials
    $mg = Mailgun::create($_ENV["EMAIL_PRIVATE_KEY"]); // For US servers
    // Now, compose and send your message.
    // $mg->messages()->send($domain, $params);
    $mg->messages()->send('sandbox03100807dd3f4ad39663ca642eb2a098.mailgun.org', [
      'from'    => 'noreply@fitnesstracker.com',
      'to'      => $to."",
      'subject' => $subject."",
      'text'    => $text.""
    ]);
  }


?>