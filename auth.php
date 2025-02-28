<?php
session_start(); // Start the session

// Function to register a new user (VERY basic - do not use in production!)
function registerUser($username, $password) {
    $users = [];
    if (file_exists('users.txt')) {
        $users = unserialize(file_get_contents('users.txt'));
    }

    if (isset($users[$username])) {
        return false; // Username already exists
    }

    $users[$username] = $password; // Store username and password (INSECURE!)
    file_put_contents('users.txt', serialize($users));
    return true;
}

// Function to log in a user (VERY basic - do not use in production!)
function loginUser($username, $password) {
    $users = [];
    if (file_exists('users.txt')) {
        $users = unserialize(file_get_contents('users.txt'));
    }

    if (isset($users[$username]) && $users[$username] === $password) {
        $_SESSION['username'] = $username;
        return true;
    }

    return false;
}

// Function to check if a user is logged in
function isLoggedIn() {
    return isset($_SESSION['username']);
}

// Function to get the logged-in username
function getLoggedInUsername() {
    return $_SESSION['username'] ?? null;
}

// Function to log out a user
function logoutUser() {
    unset($_SESSION['username']);
    session_destroy();
}
?>
