<?php

include "../includes/data.php";

$formSubmitted = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formSubmitted = true;
    // Note: this doesn't actually send an email (that needs a mail server setup).
    // For a college project, showing a "Thank you" confirmation is enough.
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - Contact Us</title>
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

            <form class="search-box" action="../index.php" method="get">
                <input type="text" name="search" placeholder="Search jerseys...">
                <button type="submit">Search</button>
            </form>

            <div class="header-links">
                <a href="login.php">Login</a>
                <a href="cart.php">Cart</a>
            </div>
        </div>

        <nav>
            <a href="../index.php">Home</a>
            <a href="../index.php#categories">Categories</a>
            <a href="jerseys.php">Jerseys</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>


    <section class="contact-section">

        <h2>Contact Us</h2>
        <p class="section-text">Have a question about an order or a jersey? Reach out to us.</p>

        <div class="contact-wrapper">

            <div class="contact-info">
                <h3>Get in Touch</h3>
                <p><strong>Phone:</strong> 8733934969</p>
                <p><strong>Email:</strong> rgretro43@gmail.com</p>
                <p><strong>Store Hours:</strong> Mon - Sat, 10 AM - 8 PM</p>
            </div>

            <div class="contact-form-box">

                <?php if ($formSubmitted) { ?>

                    <p class="form-success">
                        Thank you! Your message has been received. We'll get back to you soon.
                    </p>

                <?php } else { ?>

                    <form action="contact.php" method="post">
                        <input type="text" name="name" placeholder="Your Name" required>
                        <input type="email" name="email" placeholder="Your Email" required>
                        <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                        <button type="submit" class="shop-button">Send Message</button>
                    </form>

                <?php } ?>

            </div>

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