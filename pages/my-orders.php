    <?php
session_start();

include "../includes/db.php";
include "../includes/data.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION['user_id'];

$ordersQuery = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = $userId ORDER BY created_at DESC");

$orders = array();

while ($row = mysqli_fetch_assoc($ordersQuery)) {
    $itemsQuery = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = " . $row['id']);
    $items = array();
    while ($itemRow = mysqli_fetch_assoc($itemsQuery)) {
        $items[] = $itemRow;
    }
    $row['items'] = $items;
    $orders[] = $row;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - My Orders</title>
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
                <span class="welcome-text">Hi, <?php echo $_SESSION['user_name']; ?></span>
                <a href="logout.php">Logout</a>
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


    <section class="checkout-section">

        <h2>My Orders</h2>
        <p class="section-text">All orders placed while logged in to your account.</p>

        <?php if (count($orders) == 0) { ?>

            <p class="section-text" style="text-align:center;">You haven't placed any orders yet.</p>
            <p style="text-align:center;">
                <a href="jerseys.php" class="shop-button">Shop Jerseys</a>
            </p>

        <?php } else { ?>

            <?php foreach ($orders as $order) { ?>

                <div class="order-success-box" style="margin-bottom:25px;">

                    <p class="order-number">Order Number: <?php echo $order['order_number']; ?></p>
                    <p class="section-text">
                        Placed on <?php echo date("d M Y", strtotime($order['created_at'])); ?>
                        — Status: <strong><?php echo $order['status']; ?></strong>
                    </p>

                    <div class="cart-table">
                        <?php foreach ($order['items'] as $item) { ?>
                            <div class="cart-row">
                                <div class="cart-info">
                                    <h3><?php echo $item['product_name']; ?></h3>
                                    <p>Size: <?php echo $item['size']; ?></p>
                                    <p>Qty: <?php echo $item['qty']; ?></p>
                                </div>
                                <p class="cart-price">₹<?php echo $item['price']; ?></p>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="cart-total">
                        <h3>Total: ₹<?php echo $order['total_amount']; ?></h3>
                    </div>

                </div>

            <?php } ?>

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