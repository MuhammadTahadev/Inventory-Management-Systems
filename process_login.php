<?php
session_start();
require_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT User_ID, User_Name, Password FROM users WHERE User_Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["Password"])) {
            $_SESSION["loggedin"] = true;
            $_SESSION["User_ID"] = $row["User_ID"];
            $_SESSION["User_Name"] = $row["User_Name"];

            // Check if this is the user's first login
            $sql = "SELECT * FROM user_plans WHERE User_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $row["User_ID"]);
            $stmt->execute();
            $plan_result = $stmt->get_result();

            if ($plan_result->num_rows == 0) {
                // First login, redirect to select_plan.php
                header("location: select_plan.php");
            } else {
                // Check if the user has an active plan or trial
                $sql = "SELECT * FROM user_plans WHERE User_ID = ? AND (end_date > NOW() OR is_trial = 1)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $row["User_ID"]);
                $stmt->execute();
                $active_plan_result = $stmt->get_result();

                if ($active_plan_result->num_rows > 0) {
                    // User has an active plan or trial
                    header("location: Dashboard-free.php");
                } else {
                    // User needs to select a plan
                    header("location: select_plan.php");
                }
            }
            exit;
        } else {
            header("location: login.php?error=Invalid username or password");
        }
    } else {
        header("location: login.php?error=Invalid username or password");
    }
}
?>