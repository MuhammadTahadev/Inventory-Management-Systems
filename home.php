<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TST Inventory - Inventory Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
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
                    <li class="current"><a href="home.php">Home</a></li>
                    <li><a href="Features.php">Features</a></li>
                    <li><a href="pricing.php">Pricing</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Signup</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="showcase">
        <div class="container">
            <h1>Revolutionize Your Shop Management</h1>
            <p>TST Inventory: The Ultimate Inventory Management Solution for Shop Owners</p>
        </div>
        
    </section>

    <section id="newsletter">
        <div class="container">
            <h1>Subscribe To Our Newsletter</h1>
            <form>
                <div class="input-group">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <input type="email" placeholder="Enter Email..." required>
                </div>
                <button type="submit" class="button_1">Subscribe</button>
            </form>
        </div>
    </section>

    <section id="boxes">
        <div class="container">
            <div class="box">
                <svg viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
                <h3>Easy to Use</h3>
                <p>Intuitive interface designed for shop owners of all tech levels.</p>
            </div>
            <div class="box">
                <svg viewBox="0 0 24 24">
                    <path d="M13 2.05v3.03c3.39.49 6 3.39 6 6.92 0 .9-.18 1.75-.5 2.54l2.6 1.53c.56-1.24.9-2.62.9-4.07 0-5.18-3.95-9.45-9-9.95zM12 19c-3.87 0-7-3.13-7-7 0-3.53 2.61-6.43 6-6.92V2.05c-5.06.5-9 4.76-9 9.95 0 5.52 4.47 10 9.99 10 3.31 0 6.24-1.61 8.06-4.09l-2.6-1.53C16.17 17.98 14.21 19 12 19z"/>
                </svg>
                <h3>Real-time Updates</h3>
                <p>Stay on top of your inventory with live stock updates.</p>
            </div>
            <div class="box">
                <svg viewBox="0 0 24 24">
                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                </svg>
                <h3>Customizable</h3>
                <p>Tailor the system to fit your unique shop needs.</p>
            </div>
        </div>
    </section>

    <section id="features">
        <div class="container">
            <h2>Key Features</h2>
            <div class="feature">
                <svg viewBox="0 0 24 24">
                    <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                </svg>
                <h3>Inventory Tracking</h3>
                <p>Keep track of your stock levels in real-time, set low stock alerts, and manage multiple locations.</p>
            </div>
            <div class="feature">
                <svg viewBox="0 0 24 24">
                    <path d="M3.5 18.49l6-6.01 4 4L22 6.92l-1.41-1.41-7.09 7.97-4-4L2 16.99z"/>
                </svg>
                <h3>Sales Analytics</h3>
                <p>Gain insights into your best-selling items, peak sales periods, and customer preferences.</p>
            </div>
        </div>
    </section>

    <section id="testimonials">
        <div class="container">
            <h2>What Our Customers Say</h2>
            <?php
        require_once "db_connection.php";

        // Fetch reviews from the database
        $sql = "SELECT r.Review, r.Buisness_Name, r.Image, u.User_Name 
                FROM reviews r 
                JOIN users u ON r.User_ID = u.User_ID 
                ORDER BY r.created_at DESC 
                LIMIT 2";  // Adjust the limit as needed
        $result = $conn->query($sql);

        if ($result === false) {
            // Query failed
            echo "<p>Error: Unable to fetch reviews. " . $conn->error . "</p>";
        } elseif ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                ?>
                <div class="testimonial">
                    <?php if ($row["Image"] && file_exists($row["Image"])): ?>
                        <img src="<?php echo htmlspecialchars($row["Image"]); ?>" alt="User Image">
                    <?php else: ?>
                        <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Default User Image">
                    <?php endif; ?>
                    <p>"<?php echo htmlspecialchars($row["Review"]); ?>"</p>
                    <p><strong>
                        <?php 
                        echo htmlspecialchars($row["Buisness_Name"] ? $row["Buisness_Name"] : $row["User_Name"]);
                        ?>
                    </strong></p>
                </div>
                <?php
            }
        } else {
            echo "<p>No reviews yet. Be the first to leave a review!</p>";
        }
        $conn->close();
        ?>
        </div>
    </section>

    <!-- <section id="pricing">
        <div class="container">
            <h2>Choose Your Plan</h2>
            <div class="price-box">
                <h3>Basic</h3>
                <div class="price">$29/month</div>
                <ul>
                    <li>Up to 500 items</li>
                    <li>Basic reporting</li>
                    <li>Email support</li>
                </ul>
                <a href="#" class="btn">Choose Plan</a>
            </div>
            <div class="price-box">
                <h3>Pro</h3>
                <div class="price">$59/month</div>
                <ul>
                    <li>Up to 2000 items</li>
                    <li>Advanced analytics</li>
                    <li>Priority support</li>
                </ul>
                <a href="#" class="btn">Choose Plan</a>
            </div>
            <div class="price-box">
                <h3>Enterprise</h3>
                <div class="price">Custom</div>
                <ul>
                    <li>Unlimited items</li>
                    <li>Custom features</li>
                    <li>24/7 support</li>
                </ul>
                <a href="#" class="btn">Contact Us</a>
            </div>
        </div>
    </section> -->

    <section id="contact">
        <div class="container">
            <h2>Get In Touch</h2>
            <form>
                <div class="input-group">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <input type="text" placeholder="Name" pattern="^[A-Za-z\s]+$" required>
                </div>
                <div class="input-group">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <input type="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/>
                    </svg>
                    <textarea placeholder="Your Message" required></textarea>
                </div>
                <button type="submit">Send</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2024 TST Inventory. All rights reserved.</p>
            <div class="social-links">
                <a href="#" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" fill="#ffffff">
                        <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                    </svg>
                </a>
                <a href="#" aria-label="Twitter">
                    <svg viewBox="0 0 24 24" fill="#ffffff">
                        <path d="M23.954 4.569a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.691 8.094 4.066 6.13 1.64 3.161a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.061a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.937 4.937 0 004.604 3.417 9.868 9.868 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63a9.936 9.936 0 002.46-2.548l-.047-.02z"/>
                    </svg>
                </a>
                <a href="#" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" fill="#ffffff">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                </a>
            </div>
        </div>
    </footer>
</body>
</html>










