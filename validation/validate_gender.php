<?php
$validation_error;

function validate_gender()
{
    global $validation_error;

    if (isset($_POST['gender'])) {
        $gender = $_POST['gender'];
        $valid_genders = array('f', 'm', 'o');

        $sanitizedGender = strtolower(trim($gender));

        if (in_array($sanitizedGender, $valid_genders)) {
            $validation_error['gender'] = "";
            return true;
        } else {
            $validation_error['gender'] = "Enter a valid gender.";
            return false;
        }
    } else {
        $validation_error['gender'] = "gender is required.";
        return false;
    }
}

?>