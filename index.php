<?php

session_start();

include "includes/data.php";

function jerseyMatchesSearch($product, $searchTerm) {
    $words = preg_split('/\s+/', trim($searchTerm));
    foreach ($words as $word) {
        if ($word == "") {
            continue;
        }
        $found = (stripos($product['name'], $word) !== false) ||
                 (stripos($product['category'], $word) !== false) ||
                 (stripos($product['edition'], $word) !== false);
        if (!$found) {
            return false;
        }
    }
    return true;
}

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";
$searchResults = array();

if ($searchTerm != "") {
    foreach ($products as $product) {
        if (jerseyMatchesSearch($product, $searchTerm)) {
            $searchResults[] = $product;
        }
    }
}

$featuredProducts = array();

foreach ($products as $product) {
    if ($product['featured'] == true) {
        $featuredProducts[] = $product;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $shopName; ?> - Football Jerseys</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <div class="top-bar">

            <div class="logo">
                <a href="index.php">
                    <img src="img/logo.png" alt="RG Retro Logo">
                </a>
            </div>

            <div class="search-wrapper">
                <form class="search-box" action="index.php" method="get" autocomplete="off">
                    <input type="text" name="search" placeholder="Search jerseys..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit">🔍</button>
                </form>
                <div class="search-suggestions"></div>
            </div>

            <div class="header-links">
                              <?php if (isset($_SESSION['user_name'])) { ?>
                    <span class="welcome-text">Hi, <?php echo $_SESSION['user_name']; ?></span>
                    <a href="pages/my-orders.php">My Orders</a>
                    <a href="pages/logout.php">Logout</a>
                <?php } else { ?>
                    <a href="pages/login.php">Login</a>
                <?php } ?>
                <a href="pages/cart.php">Cart</a>
            </div>

        </div>

        <nav>
            <a href="index.php">Home</a>
            <a href="#categories">Categories</a>
            <a href="pages/jerseys.php">Jerseys</a>
            <a href="pages/track-order.php">Track Order</a>
            <a href="pages/contact.php">Contact</a>
        </nav>
    </header>


    <?php if ($searchTerm != "") { ?>

        <section class="products" style="padding-top: 50px;">

            <h2>Search Results</h2>
            <p class="section-text">
                <span class="search-results-pill">Showing results for "<?php echo htmlspecialchars($searchTerm); ?>"</span>
            </p>

            <?php if (count($searchResults) == 0) { ?>

                <p class="section-text">No jerseys found matching your search. Try a different name or category.</p>
                <p style="text-align:center;">
                    <a href="pages/jerseys.php" class="shop-button">Browse All Jerseys</a>
                </p>

            <?php } else { ?>

                <div class="product-container">
                    <?php foreach ($searchResults as $product) { ?>
                        <div class="product-card">

                            <div class="product-image">
                                <?php if ($product['badge'] == "New") { ?>
                                    <span class="badge badge-new">New</span>
                                <?php } elseif ($product['badge'] == "Bestseller") { ?>
                                    <span class="badge badge-bestseller">Bestseller</span>
                                <?php } ?>
                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            </div>

                            <div class="product-details">
                                <?php if ($product['edition'] != "") { ?>
                                    <p class="edition"><?php echo $product['edition']; ?></p>
                                <?php } ?>
                                                                <h3><?php echo $product['name']; ?></h3>
                                <?php if ($product['discount_percent'] > 0) { ?>
                                    <p class="price">
                                        <span class="original-price">₹<?php echo $product['original_price_value']; ?></span>
                                        <?php echo $product['price']; ?>
                                        <span class="discount-tag"><?php echo $product['discount_percent']; ?>% OFF</span>
                                    </p>
                                <?php } else { ?>
                                    <p class="price"><?php echo $product['price']; ?></p>
                                <?php } ?>

                                <form action="pages/cart-add.php" method="post" class="add-to-cart-form">
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

                                <a href="pages/product.php?id=<?php echo $product['id']; ?>" class="view-button">View Details</a>
                            </div>

                        </div>
                    <?php } ?>
                </div>

            <?php } ?>

        </section>

    <?php } else { ?>

            <section class="hero">
            <img src="images/messi-hero.png" alt="Lionel Messi" class="hero-player hero-player-left">
            <div class="hero-content">
                <h1>Football Jerseys for Every Fan</h1>
                <p>Find your favourite club and player jerseys at RG Retro.</p>
                <a href="#products" class="shop-button">Shop Jerseys</a>
            </div>
            <img src="images/ronaldo-hero.png" alt="Cristiano Ronaldo" class="hero-player hero-player-right">
        </section>

        <section class="categories" id="categories">
            <h2>Shop By Category</h2>
            <p class="section-text">Choose from different types of football jerseys.</p>

            <div class="category-container">
                <?php foreach ($categories as $category) { ?>
                    <div class="category-card">
                        <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" class="category-image">
                        <div class="category-text">
                            <h3><?php echo $category['name']; ?></h3>
                            <a href="pages/category.php?type=<?php echo urlencode($category['name']); ?>">View Jerseys</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>


        <section class="products" id="products">
            <h2>Featured Jerseys</h2>
            <p class="section-text">Check out some of our popular football jerseys.</p>

            <div class="product-container">
                <?php foreach ($featuredProducts as $product) { ?>
                    <div class="product-card">

                        <div class="product-image">
                            <?php if ($product['badge'] == "New") { ?>
                                <span class="badge badge-new">New</span>
                            <?php } elseif ($product['badge'] == "Bestseller") { ?>
                                <span class="badge badge-bestseller">Bestseller</span>
                            <?php } ?>
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                        </div>

                        <div class="product-details">

                            <?php if ($product['edition'] != "") { ?>
                                <p class="edition"><?php echo $product['edition']; ?></p>
                            <?php } ?>

                            <h3><?php echo $product['name']; ?></h3>
                            <p class="price"><?php echo $product['price']; ?></p>

                            <form action="pages/cart-add.php" method="post" class="add-to-cart-form">
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

                            <a href="pages/product.php?id=<?php echo $product['id']; ?>" class="view-button">View Details</a>
                        </div>

                    </div>
                <?php } ?>
            </div>
        </section>


        <section class="about">
            <h2>Why Choose RG Retro?</h2>
            <div class="about-container">

                <div class="about-box">
                    <h3>Quality Jerseys</h3>
                    <p>We provide football jerseys in different editions and sizes.</p>
                </div>

                <div class="about-box">
                    <h3>Different Clubs</h3>
                    <p>Choose jerseys from popular football clubs and national teams.</p>
                </div>

                <div class="about-box">
                    <h3>Easy Ordering</h3>
                    <p>Select your favourite jersey, add it to your cart and place your order easily.</p>
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
                <a href="index.php">Home</a>
                <a href="pages/jerseys.php">Jerseys</a>
                <a href="pages/track-order.php">Track Order</a>
                <a href="pages/contact.php">Contact</a>
            </div>

                       <div>
                <h3>Contact</h3>
                <p>Phone: 8733934969</p>
                <p>Email: rgretro43@gmail.com</p>
                <p><a href="pages/admin/login.php" style="color:#ccc; font-size:12px;">Admin Login</a></p>
            </div>

        </div>

        <div class="copyright">
            <p>&copy; <?php echo date("Y"); ?> RG Retro. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/cart.js"></script>
    <script src="js/search-suggest.js"></script>
</body>
</html>