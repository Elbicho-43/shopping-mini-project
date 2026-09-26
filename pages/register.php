<?php
session_start();

include "../includes/db.php";
include "../includes/data.php";

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];

    $checkEmail = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");

    if (mysqli_num_rows($checkEmail) > 0) {

        $errorMessage = "An account with this email already exists. Please login instead.";

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertUser = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashedPassword')";
        mysqli_query($conn, $insertUser);

        $newUserId = mysqli_insert_id($conn);

        $_SESSION['user_id'] = $newUserId;
        $_SESSION['user_name'] = $name;

        header("Location: ../index.php");
        exit;

    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - Register</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <div class="top-bar">
            <div class="logo">
                <a href="../index.php">
                    <img src="../img/logo.png" alt="RG Retro Logo">
                </a>
            </div>

                     <div class="search-wrapper">
                <form class="search-box" action="../index.php" method="get" autocomplete="off">
                    <input type="text" name="search" placeholder="Search jerseys...">
                    <button type="submit">🔍</button>
                </form>
                <div class="search-suggestions"></div>
            </div>

            <div class="header-links">
                <a href="login.php">Login</a>
                <a href="cart.php">Cart</a>
            </div>
        </div>

        <nav>
            <a href="../index.php">Home</a>
            <a href="../index.php#categories">Categories</a>
            <a href="jerseys.php">Jerseys</a>
            <a href="track-order.php">Track Order</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>


    <section class="contact-section">

        <h2>Create an Account</h2>
        <p class="section-text">Register to track your orders and checkout faster.</p>

        <div class="auth-box">

            <?php if ($errorMessage != "") { ?>
                <p class="form-error"><?php echo $errorMessage; ?></p>
            <?php } ?>

            <form action="register.php" method="post">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="tel" name="phone" placeholder="Phone Number" required>
                <input type="password" name="password" placeholder="Password" required minlength="6">
                <button type="submit" class="shop-button">Register</button>
            </form>

            <p class="auth-switch">Already have an account? <a href="login.php">Login here</a></p>

        </div>

    </section>


    <footer>
        <div class="footer-content">
            <div>
                <h3><?php echo $shopName; ?></h3>
                <p>Your local football jersey store.</p>
            </div>
            <div>
                <h3>Quick Links</h3>
                <a href="../index.php">Home</a>
                <a href="jerseys.php">Jerseys</a>
                <a href="contact.php">Contact</a>
            </div>
            <div>
                <h3>Contact</h3>
                <p>Phone: 8733934969</p>
                <p>Email: rgretro43@gmail.com</p>
            </div>
        </div>

        <div class="copyright">
            <p>&copy; <?php echo date("Y"); ?> RG Retro. All Rights Reserved.</p>
        </div>
    </footer>
<script src="../js/search-suggest.js"></script>
</body>
</html>