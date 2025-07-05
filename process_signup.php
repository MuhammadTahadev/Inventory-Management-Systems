<?php
// Include database connection
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $company = $_POST['buisness'];

    // Validate form data
    $errors = [];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT * FROM Users WHERE User_Name = ? OR Email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = "Username or email already exists";
    }

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $stmt = $conn->prepare("INSERT INTO users (Full_Name, User_Name, Email, Phone, Password, Buisness) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fullname, $username, $email, $phone, $hashed_password, $company);

        if ($stmt->execute()) {
            // Registration successful
            header("Location: login.php?registration=success");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }

    // If there are errors, redirect back to signup page with error messages
    if (!empty($errors)) {
        $error_string = implode(",", $errors);
        header("Location: signup.php?errors=" . urlencode($error_string));
        exit();
    }
}
?>