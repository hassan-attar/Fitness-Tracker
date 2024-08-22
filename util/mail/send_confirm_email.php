<?php
// Adjust the paths to be relative to this file
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../config.php';
require __DIR__ . '/get_confirm_email.php';

function send_confirm_email($firstName, $email) {
  // $html_text = get_confirm_email($firstName);
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