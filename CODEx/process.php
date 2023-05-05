<?php

session_start();

// Connect to the database
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "users_db";

$conn = new mysqli('localhost', 'root', '', 'users_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
   

// Handle login form submission
if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email and password are valid
    $sql = "SELECT id, first_name, last_name FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found, set session variables and redirect to home page
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        header('Location: home.html');
        exit;
    } else {
        // Invalid email or password, show error message
        echo "Invalid email or password.";
    }
}

// Handle registration form submission
if (isset($_POST['register_submit'])) {
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    // Check if the passwords match
    if ($password != $confirm_password) {
        echo "Passwords do not match.";
        exit;
    }

    // Check if the email is already registered
    $sql = "SELECT id FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Email address already registered.";
        exit;
    }

    // Insert new user into the database
    $sql = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        // New user created successfully, set session variables and redirect to login page
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        header('Location: login-sign-up.html');
        echo "Successfully registered!";
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
