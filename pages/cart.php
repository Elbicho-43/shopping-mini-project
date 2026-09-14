<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_GET['remove'])) {
    $removeIndex = (int)$_GET['remove'];
    unset($_SESSION['cart'][$removeIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit;
}

$shopName = "RG Retro";
$cartTotal = 0;

foreach ($_SESSION['cart'] as $item) {
    $cartTotal += $item['price'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - Your Cart</title>
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
            <a href="../index.php#products">Jerseys</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>


    <section class="products" style="padding-top: 50px;">

        <h2>Your Cart</h2>

        <?php if (count($_SESSION['cart']) == 0) { ?>

            <p class="section-text">Your cart is empty. Go add some jerseys!</p>
            <p style="text-align:center;">
                <a href="../index.php#products" class="shop-button">Shop Jerseys</a>
            </p>

        <?php } else { ?>

            <div class="cart-table">

                <?php foreach ($_SESSION['cart'] as $index => $item) { ?>

                    <div class="cart-row">

                        <img src="../<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-img">

                        <div class="cart-info">
                            <h3><?php echo $item['name']; ?></h3>
                            <p>Size: <?php echo $item['size']; ?></p>
                            <p>Qty: <?php echo $item['qty']; ?></p>
                        </div>

                        <p class="cart-price">₹<?php echo $item['price']; ?></p>

                        <a href="cart.php?remove=<?php echo $index; ?>" class="remove-link">Remove</a>

                    </div>

                <?php } ?>

            </div>

            <div class="cart-total">
                <h3>Total: ₹<?php echo $cartTotal; ?></h3>
                <a href="checkout.php" class="shop-button">Proceed to Checkout</a>
            </div>

        <?php } ?>

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
                <a href="../index.php#products">Jerseys</a>
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