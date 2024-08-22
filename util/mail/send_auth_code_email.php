<?php
// Adjust the paths to be relative to this file
require __DIR__ . '/../../vendor/autoload.php';

function send_auth_code_email($firstName,$email,$authKey) {
  // $html_text = get_auth_code_email($firstName,$authKey);
  // $email = new \SendGrid\Mail\Mail(); 
  // $email->setFrom("h.a.develops@gmail.com", "Hassan Attar");
  // $email->setSubject("Confirm Your Email");
  // $email->addTo($email, $firstName);
  // $email->addContent(
  //     "text/html", $html_text
  // );
  // $sendgrid = new \SendGrid($_ENV["SENDGRID_API_KEY"]);
  // try {
  //     $response = $sendgrid->send($email);
  // } catch (Exception $e) {
      
  // }
}
?>