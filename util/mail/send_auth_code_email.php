<?php
  require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/vendor/autoload.php";
  require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/config.php";
  require $_SERVER['DOCUMENT_ROOT']."/Fitness-Tracker/util/mail/get_auth_code_email.php";

  use Mailgun\Mailgun;
  function send_auth_code_email($firstName,$email,$authKey) {
    // First, instantiate the SDK with your API credentials
    $mg = Mailgun::create($_ENV["EMAIL_PRIVATE_KEY"]); // For US servers
    // Now, compose and send your message.
    // $mg->messages()->send($domain, $params);
    $html_text = get_auth_code_email($firstName,$authKey);
      
    $mg->messages()->send('sandbox091c75e578af461a8c186004ae67fa8f.mailgun.org', [
      'from'    => 'noreply@fitnesstracker.com',
      'to'      => $email."",
      'subject' => "Email Confirmed",
      'html'    => $html_text
    ]);
    
  }