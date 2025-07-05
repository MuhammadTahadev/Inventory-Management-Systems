<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TST Inventory - Inventory Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="signup.css">
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
                    <li><a href="home.php">Home</a></li>
                    <li><a href="Features.php">Features</a></li>
                    <li><a href="pricing.php">Pricing</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li class="current"><a href="signup.php">Signup</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section id="signup-form">
        <div class="container">
            <div class="form-wrapper">
                <h2>Create Your Account</h2>
                <?php
if (isset($_GET['errors'])) {
    $errors = explode(",", $_GET['errors']);
    echo "<div class='error-messages'>";
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
    echo "</div>";
}
?>
                <form action="process_signup.php" method="POST">
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <div class="input-icon">
                            <input type="text" id="fullname" name="fullname" placeholder="Full Name" required pattern="^[A-Za-z\s]+$" autocomplete="off">
                            <svg viewBox="0 0 24 24" fill="#ccc">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-icon">
                            <input type="text" id="username" name="username" placeholder="Username" required autocomplete="off">
                            <svg viewBox="0 0 24 24" fill="#ccc">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-icon">
                            <input type="email" id="email" name="email" placeholder="Email Address" required autocomplete="off">
                            <svg viewBox="0 0 24 24" fill="#ccc">
                                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <div class="input-icon">
                        <input type="tel" id="phone" name="phone" placeholder="Phone Number" required autocomplete="off" pattern="[0-9+\-\(\) ]{6,20}">
                        <p style = "font-size: 12px; color: #707070; margin-top: 5px;">Please do mention the country code</p>
                            <svg class = "phone-icon" viewBox="0 0 24 24" fill="#ccc">
                                <path d="M6.62 10.79a15.91 15.91 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27c1.21.49 2.53.76 3.88.76a1 1 0 011 1v3.5a1 1 0 01-1 1A17.92 17.92 0 012 4a1 1 0 011-1h3.5a1 1 0 011 1c0 1.35.26 2.67.76 3.88a1 1 0 01-.27 1.11l-2.2 2.2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-icon">
                            <input type="password" id="password" name="password" placeholder="Password" required autocomplete="off">
                            <svg viewBox="0 0 24 24" fill="#ccc">
                                <path d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-6V9c0-3.31-2.69-6-6-6S6 5.69 6 9v2H4v12h16V11h-2zm-2 0H8V9c0-2.21 1.79-4 4-4s4 1.79 4 4v2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <div class="input-icon">
                            <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" required autocomplete="off">
                            <svg viewBox="0 0 24 24" fill="#ccc">
                                <path d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-6V9c0-3.31-2.69-6-6-6S6 5.69 6 9v2H4v12h16V11h-2zm-2 0H8V9c0-2.21 1.79-4 4-4s4 1.79 4 4v2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="buisness">Business Name (Optional)</label>
                        <div class="input-icon">
                            <input type="text" id="buisness" name="buisness" placeholder="Business Name" autocomplete="off">
                            <svg viewBox="0 0 24 24" fill="#ccc">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">I agree to the <a href="#">terms and conditions</a></label>
                    </div>
                    <button type="submit" class="btn">Sign Up</button>
                </form>
                <div class="login-link">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
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


