<?php
session_start();

include "../../includes/db.php";
include "../../includes/admin-auth.php";

$productsQuery = mysqli_query($conn, "SELECT * FROM products ORDER BY category ASC, id ASC");

$ordersQuery = mysqli_query($conn, "SELECT * FROM orders ORDER BY created_at DESC LIMIT 50");

$statusOptions = array("Placed", "Packed", "Shipped", "Delivered");

?>
<!DOCTYPE html>
<html>
<head>
    <title>RG Retro - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

    <header>
        <div class="top-bar">
            <div class="logo">
                <a href="../../index.php">
                    <img src="../../img/logo.png" alt="RG Retro Logo">
                </a>
            </div>
            <div class="header-links">
                <span class="welcome-text" style="color:#123b82;">Admin: <?php echo $_SESSION['admin_username']; ?></span>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </header>

    <section class="admin-section">

        <h2>Manage Products &amp; Stock</h2>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price (₹)</th>
                    <th>Stock</th>
                    <th>Badge</th>
                    <th>Featured</th>
                    <th></th>
                </tr>

                <?php while ($product = mysqli_fetch_assoc($productsQuery)) { ?>

                    <tr>
                        <form action="update-product.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                            <td><img src="../../<?php echo $product['image']; ?>" class="admin-thumb"></td>
                            <td><?php echo $product['name']; ?><br><small style="color:#888;"><?php echo $product['category']; ?></small></td>
                            <td><?php echo $product['category']; ?></td>
                            <td><input type="number" name="price_value" value="<?php echo $product['price_value']; ?>" class="admin-input-small"></td>
                            <td><input type="number" name="stock" value="<?php echo $product['stock']; ?>" class="admin-input-small"></td>
                            <td>
                             
                                                        <td><input type="number" name="discount_percent" value="<?php echo $product['discount_percent']; ?>" class="admin-input-small"></td>
                                    <option value="" <?php echo $product['badge'] == "" ? "selected" : ""; ?>>None</option>
                                    <option value="New" <?php echo $product['badge'] == "New" ? "selected" : ""; ?>>New</option>
                                    <option value="Bestseller" <?php echo $product['badge'] == "Bestseller" ? "selected" : ""; ?>>Bestseller</option>
                                </select>
                            </td>
                            <td style="text-align:center;">
                                <input type="checkbox" name="featured" <?php echo $product['featured'] == 1 ? "checked" : ""; ?>>
                            </td>
                            <td><button type="submit" class="admin-save-btn">Save</button></td>
                        </form>
                    </tr>

                <?php } ?>

            </table>
        </div>

    </section>


    <section class="admin-section">

        <h2>Manage Orders</h2>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Total (₹)</th>
                    <th>Payment</th>
                    <th>Placed On</th>
                    <th>Status</th>
                    <th></th>
                </tr>

                <?php while ($order = mysqli_fetch_assoc($ordersQuery)) { ?>

                    <tr>
                        <form action="update-order-status.php" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                            <td><?php echo $order['order_number']; ?></td>
                            <td><?php echo $order['customer_name']; ?></td>
                            <td><?php echo $order['phone']; ?></td>
                            <td><?php echo $order['total_amount']; ?></td>
                            <td><?php echo $order['payment_method']; ?></td>
                            <td><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>
                            <td>
                                <select name="status" class="admin-select">
                                    <?php foreach ($statusOptions as $option) { ?>
                                        <option value="<?php echo $option; ?>" <?php echo $order['status'] == $option ? "selected" : ""; ?>><?php echo $option; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td><button type="submit" class="admin-save-btn">Update</button></td>
                        </form>
                    </tr>

                <?php } ?>

            </table>
        </div>

    </section>

</body>
</html>