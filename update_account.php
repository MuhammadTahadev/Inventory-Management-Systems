<?php
session_start();
require_once "db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$username = $_SESSION["User_Name"];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["currentPassword"];
    $new_username = $_POST["newUsername"];
    $new_password = $_POST["newPassword"];
    $confirm_new_password = $_POST["confirmNewPassword"];

    // Verify current password
    $sql = "SELECT Password FROM users WHERE User_Name = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $hashed_password);
                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($current_password, $hashed_password)) {
                        // Current password is correct, proceed with updates
                        $update_username = false;
                        $update_password = false;

                        if (!empty($new_username) && $new_username !== $username) {
                            $update_username = true;
                        }

                        if (!empty($new_password) && $new_password === $confirm_new_password) {
                            $update_password = true;
                        }

                        if ($update_username || $update_password) {
                            $sql = "UPDATE users SET ";
                            $types = "";
                            $params = array();

                            if ($update_username) {
                                $sql .= "User_Name = ?, ";
                                $types .= "s";
                                $params[] = $new_username;
                            }

                            if ($update_password) {
                                $sql .= "Password = ?, ";
                                $types .= "s";
                                $params[] = password_hash($new_password, PASSWORD_DEFAULT);
                            }

                            $sql = rtrim($sql, ", ") . " WHERE User_Name = ?";
                            $types .= "s";
                            $params[] = $username;

                            if ($stmt = mysqli_prepare($conn, $sql)) {
                                mysqli_stmt_bind_param($stmt, $types, ...$params);
                                if (mysqli_stmt_execute($stmt)) {
                                    $message = "Account updated successfully.";
                                    if ($update_username) {
                                        $_SESSION["User_Name"] = $new_username;
                                    }
                                } else {
                                    $message = "Oops! Something went wrong. Please try again later.";
                                }
                            }
                        } else {
                            $message = "No changes were made.";
                        }
                    } else {
                        $message = "Current password is incorrect.";
                    }
                }
            } else {
                $message = "User not found.";
            }
        } else {
            $message = "Oops! Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
}

$_SESSION['account_update_message'] = $message;
header("location: Dashboard-free.php");
exit;
?>