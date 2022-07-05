<?php

if (isset($_POST["submit"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordRepeat"];

    require_once '../functions.php';
    require_once '../config.php';
    // TODO make this all one function
    if (EmptyInputSignup($username, $email, $password,  $passwordRepeat) !== false) {
        header("location: ../../signup?error=Please fill in all fields!");
        exit();
    }
    if (InvalidUsername($username) !== false) {
        header("location: ../../signup?error=Username contains forbidden characters.");
        exit();
    }
    if (InvalidUsernameLength($username) !== false) {
        header("location: ../../signup?error=Username must be atleast 3 characters but not exceed 20.");
        exit();
    }
    if (InvalidEmail($email) !== false) {
        header("location: ../../signup?error=Invalid email. This usually means that you entered your email address incorrectly or your email provider is on our blocked list to prevent spam.");
        exit();
    }
    if (InvalidPasswordLength($password) !== false) {
        header("location: ../../signup?error=Password must be atleast 8 characters but not exceed 50 characters.");
        exit();
    }
    if (InvalidPasswordMatch($password, $passwordRepeat) !== false) {
        header("location: ../../signup?error=Your passwords do not match!");
        exit();
    }
    if (UsernameExists($conn, $username) !== false) {
        header("location: ../../signup?error=The username you entered has already been registered by another user. Try another variation of your desired username or pick a new one altogether.");
        exit();
    }

    CreateUser($conn, $username, $email, $password);
} else {
    header('location: ../../signup?error=Access Denied!');
}