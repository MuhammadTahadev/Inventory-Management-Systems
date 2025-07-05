<?php
session_start();
require_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["User_ID"];  // Make sure this session variable is set when the user logs in
    $review_description = $_POST["reviewDescription"];
    $business_name = $_POST["businessName"];
    
    // Handle file upload
    $image_path = null;
    if (isset($_FILES["userImage"]) && $_FILES["userImage"]["error"] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["userImage"]["name"]);
        if (move_uploaded_file($_FILES["userImage"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        }
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO reviews (Review, Buisness_Name, Image, User_ID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi",$review_description, $business_name, $image_path, $user_id);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Review submitted successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>