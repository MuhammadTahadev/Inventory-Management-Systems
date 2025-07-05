<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['User_ID'];

    // Handle supplier addition
    if (isset($_POST["supplierName"])) {
        handleSupplierAddition($conn, $user_id);
    }
    // Handle product addition
    elseif (isset($_POST['productName'])) {
        handleProductAddition($conn, $user_id);
    }
    // Handle category addition
    elseif (isset($_POST['addCategory'])) {
        handleCategoryAddition($conn, $user_id);
    }
}

function handleSupplierAddition($conn, $user_id) {
    $supplierName = $_POST['supplierName'];
    $supplierContact = $_POST['supplierContact'];
    $supplierEmail = $_POST['supplierEmail'];

    // Validate input
    if (empty($supplierName) || empty($supplierContact) || empty($supplierEmail)) {
        $_SESSION['error_message'] = "All fields are required for adding a supplier.";
        header("Location: Dashboard-free.php");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM suppliers WHERE Email = ? AND User_ID = ?");
    $stmt->bind_param("si", $supplierEmail, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Email already exists for this user";
        header("Location: Dashboard-free.php");
        exit();
    }

    // Get the maximum Supplier_ID for this user
    $stmt = $conn->prepare("SELECT MAX(Supplier_ID) as max_id FROM suppliers WHERE User_ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $next_supplier_id = ($row['max_id'] ?? 0) + 1;

    // Insert new supplier
    $sql = "INSERT INTO suppliers (User_ID, Supplier_ID, Name, Contact, Email) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $user_id, $next_supplier_id, $supplierName, $supplierContact, $supplierEmail);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Supplier added successfully";
    } else {
        $_SESSION['error_message'] = "Error adding supplier: " . $conn->error;
    }

    header("Location: Dashboard-free.php");
    exit();
}

    if (!empty($errors)) {
        $_SESSION['error_message'] = implode(", ", $errors);
        header("Location: Dashboard-free.php");
        exit();
    }

    function handleProductAddition($conn, $user_id) {
        $productName = $_POST['productName'];
        $productCategory = $_POST['productCategory']; // This should now be the Category_ID
        $productQuantity = $_POST['productQuantity'];
        $productPrice = $_POST['productPrice'];
        $productDescription = $_POST['productDescription'];
    
        $errors = [];
    
        // Validate input
        if (empty($productName)) {
            $errors[] = "Product name is required";
        }
        if (empty($productCategory)) {
            $errors[] = "Product category is required";
        }
        if (!is_numeric($productQuantity) || $productQuantity < 0) {
            $errors[] = "Product quantity must be a non-negative number";
        }
        if (!is_numeric($productPrice) || $productPrice < 0) {
            $errors[] = "Product price must be a non-negative number";
        }
    
        if (empty($errors)) {
            // Get the maximum Product_ID for this user
            $stmt = $conn->prepare("SELECT MAX(Product_ID) as max_id FROM products WHERE User_ID = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $next_product_id = ($row['max_id'] ?? 0) + 1;
    
            $sql = "INSERT INTO products (User_ID, Product_ID, Name, Category_ID, Quantity, Price, Description) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisiids", $user_id, $next_product_id, $productName, $productCategory, $productQuantity, $productPrice, $productDescription);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Product added successfully";
            } else {
                $_SESSION['error_message'] = "Error adding product: " . $conn->error;
            }
        } else {
            $_SESSION['error_message'] = implode(", ", $errors);
        }
    
        header("Location: Dashboard-free.php");
        exit();
    }

function handleCategoryAddition($conn, $user_id) {
    $newCategoryName = trim($_POST['newCategoryName']);

    if (!empty($newCategoryName)) {
        // Get the maximum Category_ID for this user
        $max_id_query = "SELECT MAX(Category_ID) as max_id FROM categories WHERE User_ID = ?";
        $stmt = $conn->prepare($max_id_query);
        if ($stmt === false) {
            $_SESSION['error_message'] = "Error preparing max ID query: " . $conn->error;
            header("Location: Dashboard-free.php");
            exit();
        }
        
        $stmt->bind_param("i", $user_id);
        if (!$stmt->execute()) {
            $_SESSION['error_message'] = "Error executing max ID query: " . $stmt->error;
            header("Location: Dashboard-free.php");
            exit();
        }
        
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $next_category_id = ($row['max_id'] ?? 0) + 1;
        $stmt->close();

        // Insert new category
        $insert_query = "INSERT INTO categories (Category_ID, Name, User_ID) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        if ($stmt === false) {
            $_SESSION['error_message'] = "Error preparing insert query: " . $conn->error;
            header("Location: Dashboard-free.php");
            exit();
        }
        
        $stmt->bind_param("isi", $next_category_id, $newCategoryName, $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Category added successfully!";
        } else {
            $_SESSION['error_message'] = "Error adding category: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Category name cannot be empty.";
    }
    
    header("Location: Dashboard-free.php");
    exit();
}

?>