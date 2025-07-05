<?php
session_start();
require_once "db_connection.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["User_ID"];
    $plan_type = $_POST["plan_type"];
    $start_date = date("Y-m-d H:i:s");
    $is_trial = ($plan_type == "trial") ? 1 : 0;

    if ($is_trial) {
        $end_date = date("Y-m-d H:i:s", strtotime("+14 days"));
    } else {
        // For paid plans, set end_date to 1 month from now
        $end_date = date("Y-m-d H:i:s", strtotime("+1 month"));
    }

    $sql = "INSERT INTO user_plans (User_ID, plan_type, start_date, end_date, is_trial) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssi", $user_id, $plan_type, $start_date, $end_date, $is_trial);

    if ($stmt->execute()) {
        header("location: Dashboard-free.php");
        exit;
    } else {
        $error = "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Plan - TST Inventory</title>
    <link rel="stylesheet" href="pricing.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight">TST</span>Inventory</h1>
            </div>
            <nav>
                <ul>
                    <li class="current"><a href="select_plan.php">Select Plan</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="pricing-plans">
        <div class="container">
            <div class="plan basic">
                <h2>Basic</h2>
                <div class="price">$9.99<span>/month</span></div>
                <ul>
                    <li>Up to 100 products</li>
                    <li>Basic reporting</li>
                    <li>Email support</li>
                    <li>1 user account</li>
                </ul>
                <div class="button-container">
                    <form method="POST" action = "select_plan.php">
                        <input type="hidden" name="plan_type" value="basic">
                        <button type="submit" class="btn">Choose Basic</button>
                    </form>
                </div>
            </div>
            <div class="plan pro">
                <div class="featured-label">Most Popular</div>
                <h2>Pro</h2>
                <div class="price">$24.99<span>/month</span></div>
                <ul>
                    <li>Up to 1,000 products</li>
                    <li>Advanced reporting</li>
                    <li>Priority email support</li>
                    <li>5 user accounts</li>
                    <li>API access</li>
                </ul>
                <div class="button-container">
                    <form method="POST" action = "select_plan.php">
                        <input type="hidden" name="plan_type" value="pro">
                        <button type="submit" class="btn">Choose Pro</button>
                    </form>
                </div>
            </div>
            <div class="plan enterprise">
                <h2>Enterprise</h2>
                <div class="price">$99.99<span>/month</span></div>
                <ul>
                    <li>Unlimited products</li>
                    <li>Custom reporting</li>
                    <li>24/7 phone & email support</li>
                    <li>Unlimited user accounts</li>
                    <li>API access</li>
                </ul>
                <div class="button-container">
                    <form method="POST" action = "select_plan.php">
                        <input type="hidden" name="plan_type" value="enterprise">
                        <button type="submit" class="btn">Choose Enterprise</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <section id="pricing-cta">
        <div class="container">
            <h2>Not sure which plan to choose?</h2>
            <p>Start with our risk-free trial and explore all features!</p>
            <form method="POST" action = "select_plan.php">
                <input type="hidden" name="plan_type" value="trial">
                <button type="submit" class="btn">Start Your Free Trial</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 TST Inventory. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>