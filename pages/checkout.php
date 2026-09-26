<?php
session_start();

include "../includes/db.php";
include "../includes/data.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (!isset($_SESSION['applied_coupon'])) {
    $_SESSION['applied_coupon'] = null;
}

$couponMessage = "";

// Handle "Apply Coupon" button
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['apply_coupon'])) {

    $enteredCode = mysqli_real_escape_string($conn, trim($_POST['coupon_code']));

    $couponQuery = mysqli_query($conn, "SELECT * FROM coupons WHERE code = '$enteredCode' AND active = 1");

    if (mysqli_num_rows($couponQuery) > 0) {
        $coupon = mysqli_fetch_assoc($couponQuery);
        $_SESSION['applied_coupon'] = array(
            "code" => $coupon['code'],
            "discount_percent" => $coupon['discount_percent']
        );
        $couponMessage = "Coupon applied! " . $coupon['discount_percent'] . "% off your order.";
    } else {
        $_SESSION['applied_coupon'] = null;
        $couponMessage = "Invalid or expired coupon code.";
    }

}

// Work out current cart totals (used both for display and for placing the order)
$orderItems = $_SESSION['cart'];
$subtotal = 0;

foreach ($orderItems as $item) {
    $subtotal += $item['price'];
}

$discountPercent = $_SESSION['applied_coupon'] != null ? $_SESSION['applied_coupon']['discount_percent'] : 0;
$couponCodeUsed = $_SESSION['applied_coupon'] != null ? $_SESSION['applied_coupon']['code'] : null;
$discountAmount = round($subtotal * $discountPercent / 100);
$orderTotal = $subtotal - $discountAmount;

$orderPlaced = false;
$orderNumber = "";
$placedOrderItems = array();
$placedSubtotal = 0;
$placedDiscount = 0;
$placedTotal = 0;
$placedCoupon = null;

// Handle "Place Order" button
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order']) && count($orderItems) > 0) {

    $customerName = mysqli_real_escape_string($conn, $_POST['name']);
    $customerPhone = mysqli_real_escape_string($conn, $_POST['phone']);
    $customerAddress = mysqli_real_escape_string($conn, $_POST['address']);
    $paymentMethod = mysqli_real_escape_string($conn, $_POST['payment_method']);

    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : "NULL";
    $couponSqlValue = $couponCodeUsed != null ? "'" . mysqli_real_escape_string($conn, $couponCodeUsed) . "'" : "NULL";

    $insertOrder = "INSERT INTO orders (order_number, user_id, customer_name, phone, address, payment_method, total_amount, status, coupon_code, discount_amount)
                     VALUES ('TEMP', $userId, '$customerName', '$customerPhone', '$customerAddress', '$paymentMethod', $orderTotal, 'Placed', $couponSqlValue, $discountAmount)";

    mysqli_query($conn, $insertOrder);
    $newOrderId = mysqli_insert_id($conn);

    $orderNumber = "RG" . date("ymd") . str_pad($newOrderId, 4, "0", STR_PAD_LEFT);
    mysqli_query($conn, "UPDATE orders SET order_number = '$orderNumber' WHERE id = $newOrderId");

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
    $placedOrderItems = $orderItems;
    $placedSubtotal = $subtotal;
    $placedDiscount = $discountAmount;
    $placedTotal = $orderTotal;
    $placedCoupon = $couponCodeUsed;

    $_SESSION['cart'] = array();
    $_SESSION['applied_coupon'] = null;

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
                    <?php foreach ($placedOrderItems as $item) { ?>
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
                    <p>Subtotal: ₹<?php echo $placedSubtotal; ?></p>
                    <?php if ($placedCoupon != null) { ?>
                        <p style="color:#1fa66b;">Coupon "<?php echo $placedCoupon; ?>" applied: -₹<?php echo $placedDiscount; ?></p>
                    <?php } ?>
                    <h3>Total Paid on Delivery: ₹<?php echo $placedTotal; ?></h3>
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

                    <div class="coupon-box">
                        <form action="checkout.php" method="post">
                            <input type="text" name="coupon_code" placeholder="Enter coupon code" value="<?php echo $couponCodeUsed != null ? $couponCodeUsed : ''; ?>">
                            <button type="submit" name="apply_coupon" value="1" class="admin-save-btn">Apply</button>
                        </form>
                        <?php if ($couponMessage != "") { ?>
                            <p class="coupon-message"><?php echo $couponMessage; ?></p>
                        <?php } ?>
                    </div>

                    <div class="cart-total">
                        <p>Subtotal: ₹<?php echo $subtotal; ?></p>
                        <?php if ($discountPercent > 0) { ?>
                            <p style="color:#1fa66b;">Discount (<?php echo $couponCodeUsed; ?>): -₹<?php echo $discountAmount; ?></p>
                        <?php } ?>
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

                        <button type="submit" name="place_order" value="1" class="shop-button">Place Order</button>
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