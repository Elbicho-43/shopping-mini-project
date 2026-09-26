<?php

include "../includes/data.php";

$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$selectedProduct = null;

foreach ($products as $product) {
    if ($product['id'] == $productId) {
        $selectedProduct = $product;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - <?php echo $selectedProduct != null ? $selectedProduct['name'] : "Product Not Found"; ?></title>
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


    <?php if ($selectedProduct == null) { ?>

        <section class="products" style="padding-top: 50px; text-align: center;">
            <h2>Product Not Found</h2>
            <p class="section-text">This jersey doesn't exist or may have been removed.</p>
            <a href="jerseys.php" class="shop-button">Browse All Jerseys</a>
        </section>

    <?php } else {

        $hasBack = !empty($selectedProduct['back_image']);
        $hasLogo = !empty($selectedProduct['logo_image']);

    ?>

        <section class="product-detail-section">

            <div class="product-detail-wrapper">

                <div class="product-detail-image">
                    <?php if ($selectedProduct['badge'] == "New") { ?>
                        <span class="badge badge-new">New</span>
                    <?php } elseif ($selectedProduct['badge'] == "Bestseller") { ?>
                        <span class="badge badge-bestseller">Bestseller</span>
                    <?php } ?>

                    <div class="product-gallery">

                        <input type="radio" name="productGallery" id="galleryFront" class="gallery-radio" checked>
                        <?php if ($hasBack) { ?>
                            <input type="radio" name="productGallery" id="galleryBack" class="gallery-radio">
                        <?php } ?>
                        <?php if ($hasLogo) { ?>
                            <input type="radio" name="productGallery" id="galleryLogo" class="gallery-radio">
                        <?php } ?>

                        <div class="gallery-main">
                            <img src="../<?php echo $selectedProduct['image']; ?>" alt="<?php echo $selectedProduct['name']; ?> front" class="gallery-img gallery-img-front">
                            <?php if ($hasBack) { ?>
                                <img src="../<?php echo $selectedProduct['back_image']; ?>" alt="<?php echo $selectedProduct['name']; ?> back" class="gallery-img gallery-img-back">
                            <?php } ?>
                            <?php if ($hasLogo) { ?>
                                <img src="../<?php echo $selectedProduct['logo_image']; ?>" alt="<?php echo $selectedProduct['name']; ?> logo" class="gallery-img gallery-img-logo">
                            <?php } ?>
                        </div>

                        <?php if ($hasBack || $hasLogo) { ?>
                            <div class="gallery-thumbs">
                                <label for="galleryFront" class="gallery-thumb-label gallery-thumb-front">
                                    <img src="../<?php echo $selectedProduct['image']; ?>" alt="Front view">
                                </label>
                                <?php if ($hasBack) { ?>
                                    <label for="galleryBack" class="gallery-thumb-label gallery-thumb-back">
                                        <img src="../<?php echo $selectedProduct['back_image']; ?>" alt="Back view">
                                    </label>
                                <?php } ?>
                                <?php if ($hasLogo) { ?>
                                    <label for="galleryLogo" class="gallery-thumb-label gallery-thumb-logo">
                                        <img src="../<?php echo $selectedProduct['logo_image']; ?>" alt="Logo close-up">
                                    </label>
                                <?php } ?>
                            </div>
                        <?php } ?>

                    </div>
                </div>

                <div class="product-detail-info">

                    <?php if ($selectedProduct['edition'] != "") { ?>
                        <p class="edition"><?php echo $selectedProduct['edition']; ?></p>
                    <?php } ?>

                    <h1><?php echo $selectedProduct['name']; ?></h1>

                    <p class="product-detail-category">Category: <?php echo $selectedProduct['category']; ?></p>

                    <?php if ($selectedProduct['discount_percent'] > 0) { ?>
                        <p class="price">
                            <span class="original-price">₹<?php echo $selectedProduct['original_price_value']; ?></span>
                            <?php echo $selectedProduct['price']; ?>
                            <span class="discount-tag"><?php echo $selectedProduct['discount_percent']; ?>% OFF</span>
                        </p>
                    <?php } else { ?>
                        <p class="price"><?php echo $selectedProduct['price']; ?></p>
                    <?php } ?>

                    <p class="product-detail-description">
                        <?php
                        if (isset($selectedProduct['description']) && $selectedProduct['description'] != "") {
                            echo $selectedProduct['description'];
                        } else {
                            echo "This " . $selectedProduct['name'] . " is made from breathable, lightweight fabric designed for both match day and everyday wear. It carries the classic design fans recognize, with stitched detailing and a comfortable athletic fit. A great pick for any football jersey collection.";
                        }
                        ?>
                    </p>

                    <form action="cart-add.php" method="post" class="add-to-cart-form detail-form">
                        <input type="hidden" name="product_id" value="<?php echo $selectedProduct['id']; ?>">
                        <label for="size">Select Size:</label>
                        <select name="size" id="size" class="size-select">
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L" selected>L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                        <button type="submit" class="add-cart-button">Add to Cart</button>
                    </form>

                    <a href="jerseys.php" class="view-button">Back to All Jerseys</a>

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

    <script src="../js/cart.js"></script>
    <script src="../js/search-suggest.js"></script>
</body>
</html>