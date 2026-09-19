<?php
session_start();

include "../includes/db.php";
include "../includes/data.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$orderPlaced = false;
$orderNumber = "";
$orderItems = array();
$orderTotal = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_SESSION['cart']) > 0) {

    $orderItems = $_SESSION['cart'];

    foreach ($orderItems as $item) {
        $orderTotal += $item['price'];
    }

    $customerName = mysqli_real_escape_string($conn, $_POST['name']);
    $customerPhone = mysqli_real_escape_string($conn, $_POST['phone']);
    $customerAddress = mysqli_real_escape_string($conn, $_POST['address']);
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Insert the order first (temporary order_number, fixed right after)
    $insertOrder = "INSERT INTO orders (order_number, customer_name, phone, address, payment_method, total_amount, status)
                     VALUES ('TEMP', '$customerName', '$customerPhone', '$customerAddress', '$paymentMethod', $orderTotal, 'Placed')";

    mysqli_query($conn, $insertOrder);
    $newOrderId = mysqli_insert_id($conn);

    // Build a real, unique order number using the auto-increment id
    $orderNumber = "RG" . date("ymd") . str_pad($newOrderId, 4, "0", STR_PAD_LEFT);

    mysqli_query($conn, "UPDATE orders SET order_number = '$orderNumber' WHERE id = $newOrderId");

    // Insert each cart item into order_items
    foreach ($orderItems as $item) {
        $itemName = mysqli_real_escape_string($conn, $item['name']);
        $itemSize = mysqli_real_escape_string($conn, $item['size']);
        $itemPrice = (int)$item['price'];
        $itemProductId = (int)$item['id'];

        $insertItem = "INSERT INTO order_items (order_id, product_id, product_name, size, price, qty)
                        VALUES ($newOrderId, $itemProductId, '$itemName', '$itemSize', $itemPrice, 1)";

        mysqli_query($conn, $insertItem);
    }

    $orderPlaced = true;

    $_SESSION['cart'] = array();

} else {

    $orderItems = $_SESSION['cart'];

    foreach ($orderItems as $item) {
        $orderTotal += $item['price'];
    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - Checkout</title>
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


    <?php if ($orderPlaced) { ?>

        <section class="checkout-section">

            <div class="order-success-box">

                <h2>Order Placed Successfully!</h2>

                <p class="order-number">Order Number: <strong><?php echo $orderNumber; ?></strong></p>

                <p class="section-text">
                    Thank you for shopping with RG Retro. Save your order number above —
                    you can use it anytime on our <a href="track-order.php">Track Order</a> page
                    to check your delivery status.
                </p>

                <div class="cart-table">

                    <?php foreach ($orderItems as $item) { ?>

                        <div class="cart-row">

                            <img src="../<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-img">

                            <div class="cart-info">
                                <h3><?php echo $item['name']; ?></h3>
                                <p>Size: <?php echo $item['size']; ?></p>
                                <p>Qty: <?php echo $item['qty']; ?></p>
                            </div>

                            <p class="cart-price">₹<?php echo $item['price']; ?></p>

                        </div>

                    <?php } ?>

                </div>

                <div class="cart-total">
                    <h3>Total: ₹<?php echo $orderTotal; ?></h3>
                    <a href="jerseys.php" class="shop-button">Continue Shopping</a>
                </div>

            </div>

        </section>

    <?php } elseif (count($orderItems) == 0) { ?>

        <section class="checkout-section">
            <p class="section-text" style="text-align:center;">Your cart is empty. Add some jerseys before checking out.</p>
            <p style="text-align:center;">
                <a href="../index.php#categories" class="shop-button">Shop Jerseys</a>
            </p>
        </section>

    <?php } else { ?>

        <section class="checkout-section">

            <h2>Checkout</h2>
            <p class="section-text">Confirm your order and delivery details.</p>

            <div class="checkout-wrapper">

                <div class="checkout-summary">
                    <h3>Order Summary</h3>

                    <div class="cart-table">
                        <?php foreach ($orderItems as $item) { ?>
                            <div class="cart-row">
                                <img src="../<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-img">
                                <div class="cart-info">
                                    <h3><?php echo $item['name']; ?></h3>
                                    <p>Size: <?php echo $item['size']; ?></p>
                                    <p>Qty: <?php echo $item['qty']; ?></p>
                                </div>
                                <p class="cart-price">₹<?php echo $item['price']; ?></p>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="cart-total">
                        <h3>Total: ₹<?php echo $orderTotal; ?></h3>
                    </div>
                </div>

                <div class="checkout-form-box">
                    <h3>Delivery Details</h3>

                    <form action="checkout.php" method="post">
                        <input type="text" name="name" placeholder="Full Name" required>
                        <input type="tel" name="phone" placeholder="Phone Number" required>
                        <textarea name="address" placeholder="Full Delivery Address" rows="4" required></textarea>

                        <div class="payment-options">
                            <p class="payment-label">Payment Method</p>

                            <label class="payment-choice">
                                <input type="radio" name="payment_method" value="Cash on Delivery" checked>
                                Cash on Delivery
                            </label>

                            <label class="payment-choice">
                                <input type="radio" name="payment_method" value="UPI">
                                UPI
                            </label>

                            <label class="payment-choice">
                                <input type="radio" name="payment_method" value="Net Banking">
                                Net Banking
                            </label>
                        </div>

                        <button type="submit" class="shop-button">Place Order</button>
                    </form>
                </div>

            </div>

        </section>

    <?php } ?>


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