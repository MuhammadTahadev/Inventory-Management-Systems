<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

function getDataByUserId($conn, $table, $user_id) {
    $stmt = $conn->prepare("SELECT * FROM $table WHERE User_ID = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    return $stmt->get_result();
}

function getProductsByUserId($conn, $user_id) {
    $stmt = $conn->prepare("SELECT p.*, c.Name as CategoryName 
                            FROM products p 
                            LEFT JOIN categories c ON p.Category_ID = c.Category_ID 
                            WHERE p.User_ID = ? AND c.User_ID = ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ii", $user_id, $user_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . $stmt->error);
    }
    return $stmt->get_result();
}

$username = $_SESSION["User_Name"];
$user_id = $_SESSION['User_ID'];

// Replace these lines
$sql = "SELECT * FROM suppliers WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// With this line
$suppliers_result = getDataByUserId($conn, 'suppliers', $user_id);

// Replace these lines
$stmt = $conn->prepare("SELECT * FROM categories WHERE User_ID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// With this line
$categories_result = getDataByUserId($conn, 'categories', $user_id);
$products_result = getProductsByUserId($conn, $user_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboardfree.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <button type="button" id="mobileToggle" class="mobile-toggle">☰</button>
    <div class="dashboard-container">
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="message">' . htmlspecialchars($_SESSION['message']) . '</div>';
            unset($_SESSION['message']);
        }
        ?>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3 class="sidebar-title">
                    <?php echo htmlspecialchars($username); ?>'s Inventory
                </h3>
                <button id="toggleSidebar">☰</button>
            </div>
            <ul class="sidebar-menu">
                <li class="active" data-content="all-products">
                    <i class="icon">📦</i>
                    <span class="menu-text">All Products</span>
                </li>
                <li data-content="sold-products">
                    <i class="icon">💰</i>
                    <span class="menu-text">Sold Products</span>
                </li>
                <li data-content="low-stock">
                    <i class="icon">⚠️</i>
                    <span class="menu-text">Low Stock</span>
                </li>
                <li data-content="add-product">
                    <i class="icon">➕</i>
                    <span class="menu-text">Add Product</span>
                </li>
                <li data-content="categories">
                    <i class="icon">🏷️</i>
                    <span class="menu-text">Categories</span>
                </li>
                <li data-content="suppliers">
                    <i class="icon">🚚</i>
                    <span class="menu-text">Suppliers</span>
                </li>
                <li data-content="reports">
                    <i class="icon">📊</i>
                    <span class="menu-text">Basic Reports</span>
                </li>
                <li data-content="settings">
                    <i class="icon">⚙️</i>
                    <span class="menu-text">Settings</span>
                </li>
                <li data-content="Account_settings">
                    <i class="icon">🔧</i>
                    <span class="menu-text">Account Settings</span>
                </li>
                <li data-content="Review">
                    <i class="icon">📝</i>
                    <span class="menu-text">Leave a Review</span>
                </li>
                <li data-content="logout">
                    <i class="icon">🔚</i>
                    <span class="menu-text">Logout</span>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-section active" id="all-products">
                <h2>All Products</h2>
                <div class="search-bar">
                    <input type="text" id="productSearch" placeholder="Search products by category...">
                    <button>Search</button>
                </div>
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                    <?php
            while ($product = $products_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['Product_ID']) . "</td>";
                echo "<td>" . htmlspecialchars($product['Name']) . "</td>";
                echo "<td>" . htmlspecialchars($product['CategoryName']) . "</td>";
                echo "<td>" . htmlspecialchars($product['Quantity']) . "</td>";
                echo "<td>" . htmlspecialchars($product['Price']) . "</td>";
                echo "<td>
                        <button onclick='editProduct(" . $product['Product_ID'] . ")'>Edit</button>
                        <button onclick='deleteProduct(" . $product['Product_ID'] . ")'>Delete</button>
                      </td>";
                echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <div class="pagination">
                    <button>Previous</button>
                    <span id="currentPage">1</span>
                    <button>Next</button>
                </div>
            </div>

            <div class="content-section" id="sold-products">
                <h2>Sold Products</h2>
                <form action="" method="GET" class="forms">
                    <label for="dateRange">Date Range:</label>
                    <select id="dateRange" name="dateRange">
                        <option value="">ALL</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="last7days">Last 7 Days</option>
                        <option value="last30days">Last 30 Days</option>
                        <option value="thisMonth">This Month</option>
                        <option value="lastMonth">Last Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </form>
                <table class="sold-products-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                            <th>Sale Date</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody id="soldProductsTableBody">
                        <!-- Sold product rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>

            <div class="content-section" id="low-stock">
                <h2>Low Stock Products</h2>
                <table class="low-stock-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Current Quantity</th>
                            <th>Reorder Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="lowStockTableBody">
                    </tbody>
                </table>
            </div>

            <div class="content-section" id="add-product">
                <h2>Add New Product</h2>
                <form id="addProductForm" action="add_functionalities.php" method="POST">
                    <div class="form-group">
                        <label for="productName">Product Name:</label>
                        <input type="text" id="productName" name="productName" placeholder="Enter product name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="productCategory">Category:</label>
                        <select id="productCategory" name="productCategory" required>
                            <option value="" disabled selected>Select a category</option>
                            <?php
                            while ($category_row = $categories_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($category_row['Category_ID']) . "'>" . htmlspecialchars($category_row['Name']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productQuantity">Initial Quantity:</label>
                        <input type="number" id="productQuantity" name="productQuantity"
                            placeholder="Enter initial quantity" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="productPrice">Price:</label>
                        <input type="number" id="productPrice" name="productPrice" placeholder="Enter product price"
                            required min="0" step="0.01">
                    </div>
                    <div class="form-group">
                        <label for="productDescription">Description:</label>
                        <textarea id="productDescription" name="productDescription"
                            placeholder="Enter product description"></textarea>
                    </div>
                    <button class="addbtn" type="submit" name="addProduct">Add Product</button>
                </form>
            </div>

            <div class="content-section" id="categories">
                <h2>Categories</h2>
                <div class="category-list">
                    <ul id="categoryList">
                        <div class="add-category">
                            <form class="add-category" action="add_functionalities.php" method="POST">
                                <input type="text" id="newCategoryName" name="newCategoryName"
                                    placeholder="Enter new category name" required>
                                <button type="submit" name="addCategory">Add Category</button>
                            </form>
                        </div>
                    </ul>
                </div>
            </div>

            <div class="content-section" id="suppliers">
                <h2>Suppliers</h2>
                <button type="button" id="addSupplierBtn">Add New Supplier</button>
                <table class="suppliers-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="suppliersTableBody">
                        <?php
                        while ($row = $suppliers_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Supplier_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Contact']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                        echo "<td><button onclick='editSupplier(" . $row['ID'] . ")'>Edit</button> <button onclick='deleteSupplier(" . $row['ID'] . ")'>Delete</button></td>";
                        echo "</tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <div id="addSupplierModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Add New Supplier</h2>
                    <form action="add_functionalities.php" method="POST" id="addSupplierForm">
                        <div class="form-group">
                            <label for="supplierName">Name:</label>
                            <input type="text" id="supplierName" name="supplierName" placeholder="Enter supplier name"
                                pattern="^[A-Za-z\s]+$" required>
                        </div>
                        <div class="form-group">
                            <label for="supplierContact">Contact:</label>
                            <input type="text" id="supplierContact" name="supplierContact"
                                placeholder="Enter supplier contact" pattern="[0-9+\-\(\) ]{6,20}" required>
                        </div>
                        <div class="form-group">
                            <label for="supplierEmail">Email:</label>
                            <input type="email" id="supplierEmail" name="supplierEmail"
                                placeholder="Enter supplier email" required>
                        </div>
                        <button type="submit" class="addbtn">Add Supplier</button>
                    </form>
                </div>
            </div>

            <div class="content-section" id="reports">
                <h2>Basic Reports</h2>
                <div class="report-options">
                    <button>Sales Report</button>
                    <button>Inventory Report</button>
                    <button>Low Stock Report</button>
                </div>
                <div id="reportContent">
                    <!-- Report content will be dynamically added here -->
                </div>
            </div>

            <div class="content-section" id="settings">
                <h2>Settings</h2>
                <form id="settingsForm">
                    <div class="form-group">
                        <label for="companyName">Company Name:</label>
                        <input type="text" id="companyName" placeholder="Enter company name" required>
                    </div>
                    <div class="form-group">
                        <label for="currency">Currency:</label>
                        <select id="currency">
                            <option value="" disabled selected>Select currency</option>
                            <option value="USD">USD</option>
                            <option value="EUR">EUR</option>
                            <option value="GBP">GBP</option>
                            <option value="KES">KES</option>
                            <option value="PKR">PKR</option>
                            <option value="INR">INR</option>
                            <!-- Add more currency options as needed -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="lowStockThreshold">Low Stock Threshold:</label>
                        <input type="number" id="lowStockThreshold" placeholder="Enter low stock threshold" min="1"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="emailNotifications">Email Notifications:</label>
                        <input type="checkbox" id="emailNotifications">
                    </div>
                    <button class="addbtn" type="submit">Save Settings</button>
                </form>
            </div>
            <div class="content-section" id="Account_settings">
                <h2>Account Settings</h2>
                <form id="accountSettingsForm" action="update_account.php" method="POST">
                    <div class="form-group">
                        <label for="currentUsername">Current Username:</label>
                        <input type="text" id="currentUsername" name="currentUsername"
                            value="<?php echo htmlspecialchars($username); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="newUsername">New Username:</label>
                        <input type="text" id="newUsername" name="newUsername" placeholder="Enter new username">
                    </div>
                    <div class="form-group">
                        <label for="currentPassword">Current Password:</label>
                        <input type="password" id="currentPassword" name="currentPassword"
                            placeholder="Enter current password" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password:</label>
                        <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password">
                    </div>
                    <div class="form-group">
                        <label for="confirmNewPassword">Confirm New Password:</label>
                        <input type="password" id="confirmNewPassword" name="confirmNewPassword"
                            placeholder="Confirm new password">
                    </div>
                    <button class="addbtn" type="submit">Save Changes</button>
                </form>
                <?php
    if (isset($_SESSION['account_update_message'])) {
        echo '<p class="update-message">' . htmlspecialchars($_SESSION['account_update_message']) . '</p>';
        unset($_SESSION['account_update_message']);
    }
    ?>
            </div>
            <div class="content-section" id="Review">
                <h2>Leave a Review</h2>
                <form id="reviewForm" action="review.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="reviewDescription">Your Review:</label>
                        <textarea id="reviewDescription" name="reviewDescription"
                            placeholder="Tell us about your experience..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="businessName">Business Name (Optional):</label>
                        <input type="text" id="businessName" name="businessName" placeholder="Enter your business name">
                    </div>
                    <div class="form-group">
                        <label for="userImage">Upload Image (Optional):</label>
                        <input type="file" id="userImage" name="userImage" accept="image/*">
                    </div>
                    <button class="addbtn" type="submit">Submit Review</button>
                </form>
            </div>
            <div class="content-section" id="logout">
                <h2>Logout</h2>
                <div class="card">
                    <div class="card-content">
                        <p>Are you sure you want to log out?</p>
                        <div class="logout-actions">
                            <a href="end_sessions.php" class="btn btn-danger">Yes, Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</body>
<script src="dashboard.js"></script>

</html>