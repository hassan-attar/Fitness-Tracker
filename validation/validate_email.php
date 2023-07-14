<?php
$validation_error;

function validate_email()
{
    global $validation_error;

    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $sanitizedEmail)) {

            $validation_error['email'] = "";
            return true;
        } else {
            $validation_error['email'] = "Enter a valid email address.";
            return false;
        }
    } else {

        $validation_error['email'] = "email is required.";
        return false;
    }
}
?>