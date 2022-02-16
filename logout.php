<?php
require_once 'includes/config.php';
// Session has to be started to have access to sessions
require_once 'includes/init.php';

// physically remove the cookie by putting the expiry time in the past
if (isset($_COOKIE['save_login'])) {
    // physically remove the cookie by putting the expiry time in the past
    setcookie(
        'save_login',
        '',
        time() - 60
    );
    SessionMessage::set_success_messages("Logout Successfull");
}

// Remove reset token if set
if (isset($_COOKIE['reset_token'])) {
    setcookie(
        'reset_token',
        '',
        time() - 60
    );
}

// Destroy the session
session_destroy();

// redirect to login
header("Location: login.php");