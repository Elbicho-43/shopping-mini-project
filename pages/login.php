<?php
session_start();

include "../includes/db.php";
include "../includes/data.php";

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $userQuery = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($userQuery) > 0) {

        $user = mysqli_fetch_assoc($userQuery);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: ../index.php");
            exit;

        } else {
            $errorMessage = "Incorrect password. Please try again.";
        }

    } else {
        $errorMessage = "No account found with this email.";
    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - Login</title>
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

        <h2>Login</h2>
        <p class="section-text">Welcome back to RG Retro.</p>

        <div class="auth-box">

            <?php if ($errorMessage != "") { ?>
                <p class="form-error"><?php echo $errorMessage; ?></p>
            <?php } ?>

            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="shop-button">Login</button>
            </form>

            <p class="auth-switch">Don't have an account? <a href="register.php">Register here</a></p>
            <p class="auth-switch">Are you an admin? <a href="admin/login.php">Admin Login</a></p>

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

</body>
</html>