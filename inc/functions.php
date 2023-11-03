<?php


/**
 * function Sanitization
 */
function inputSanitize($input)
{
    $data = trim($input);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}


/**
 * function email Validation
 */
function emailValidation($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Email is invalid.');
    }
}

// function password Validation
function validatePassword($password, $length)
{
    if (strlen($password) < $length) {
        throw new Exception('Password must be at least ' . $length . ' characters long.');
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()\-_=+\[\]{}|;:,.<>?])[A-Za-z\d!@#$%^&*()\-_=+\[\]{}|;:,.<>?]{8,}$/', $password)) {
        throw new Exception('Password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.');
    }
}


