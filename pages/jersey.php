<?php

include "../includes/data.php";

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - All Jerseys</title>
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


    <section class="products" style="padding-top: 50px;">

        <h2>All Jerseys</h2>
        <p class="section-text">Browse every jersey we have, organized by category.</p>

        <?php foreach ($categories as $category) { ?>

            <?php
            $categoryProducts = array();
            foreach ($products as $product) {
                if ($product['category'] == $category['name']) {
                    $categoryProducts[] = $product;
                }
            }
            if (count($categoryProducts) == 0) {
                continue;
            }
            ?>

            <h3 class="category-heading"><?php echo $category['name']; ?></h3>

            <div class="product-container">

                <?php foreach ($categoryProducts as $product) { ?>

                    <div class="product-card">

                        <div class="product-image">
                            <?php if ($product['badge'] == "New") { ?>
                                <span class="badge badge-new">New</span>
                            <?php } elseif ($product['badge'] == "Bestseller") { ?>
                                <span class="badge badge-bestseller">Bestseller</span>
                            <?php } ?>
                            <img src="../<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        </div>

                        <div class="product-details">
                            <?php if ($product['edition'] != "") { ?>
                                <p class="edition"><?php echo $product['edition']; ?></p>
                            <?php } ?>
                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price"><?php echo $product['price']; ?></p>

                            <form action="cart-add.php" method="post" class="add-to-cart-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <select name="size" class="size-select">
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L" selected>L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                                <button type="submit" class="add-cart-button">Add to Cart</button>
                            </form>

                            <a href="product.php?id=<?php echo $product['id']; ?>" class="view-button">View Details</a>
                        </div>

                    </div>

                <?php } ?>

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