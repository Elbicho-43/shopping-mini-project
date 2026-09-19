<?php

include "../includes/db.php";
include "../includes/data.php";

$orderFound = null;
$orderItems = array();
$searched = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $searched = true;
    $enteredNumber = mysqli_real_escape_string($conn, $_POST['order_number']);

    $orderQuery = mysqli_query($conn, "SELECT * FROM orders WHERE order_number = '$enteredNumber'");

    if (mysqli_num_rows($orderQuery) > 0) {

        $orderFound = mysqli_fetch_assoc($orderQuery);

        $itemsQuery = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = " . $orderFound['id']);

        while ($row = mysqli_fetch_assoc($itemsQuery)) {
            $orderItems[] = $row;
        }

    }

}

$statusSteps = array("Placed", "Packed", "Shipped", "Delivered");

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - Track Order</title>
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


    <section class="checkout-section">

        <h2>Track Your Order</h2>
        <p class="section-text">Enter your order number to see its current status.</p>

        <div class="track-form-box">
            <form action="track-order.php" method="post">
                <input type="text" name="order_number" placeholder="e.g. RG2609190001" value="<?php echo $searched ? htmlspecialchars($_POST['order_number']) : ''; ?>" required>
                <button type="submit" class="shop-button">Track Order</button>
            </form>
        </div>

        <?php if ($searched && $orderFound == null) { ?>

            <p class="section-text" style="color:#e3262e; font-weight:bold;">
                No order found with that number. Please check and try again.
            </p>

        <?php } elseif ($orderFound != null) { ?>

            <div class="order-success-box">

                <p class="order-number">Order Number: <strong><?php echo $orderFound['order_number']; ?></strong></p>
                <p class="section-text">Placed on <?php echo date("d M Y", strtotime($orderFound['created_at'])); ?></p>

                <div class="status-tracker">
                    <?php foreach ($statusSteps as $index => $step) { ?>
                        <?php
                        $currentIndex = array_search($orderFound['status'], $statusSteps);
                        $isDone = ($index <= $currentIndex);
                        ?>
                        <div class="status-step <?php echo $isDone ? 'status-done' : ''; ?>">
                            <div class="status-dot"></div>
                            <p><?php echo $step; ?></p>
                        </div>
                    <?php } ?>
                </div>

                <div class="cart-table" style="margin-top:30px;">
                    <?php foreach ($orderItems as $item) { ?>
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
                    <h3>Total: ₹<?php echo $orderFound['total_amount']; ?></h3>
                    <p class="section-text">Payment Method: <?php echo $orderFound['payment_method']; ?></p>
                </div>

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