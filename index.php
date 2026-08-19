<?php

$shopName = "RG Retro";

$categories = array(
    "Half Sleeve Jerseys",
    "Full Sleeve Jerseys",
    "Fan Edition",
    "Player Edition",
    "Jersey Kits"
);

$products = array(
    array(
        "name" => "Real Madrid Home Jersey",
        "edition" => "Player Edition",
        "price" => "₹899",
        "image" => "images/jersey1.jpg"
    ),
    array(
        "name" => "Barcelona Home Jersey",
        "edition" => "Fan Edition",
        "price" => "₹799",
        "image" => "images/jersey2.jpg"
    ),
    array(
        "name" => "Manchester United Jersey",
        "edition" => "Player Edition",
        "price" => "₹899",
        "image" => "images/jersey3.jpg"
    ),
    array(
        "name" => "Argentina Home Jersey",
        "edition" => "Fan Edition",
        "price" => "₹799",
        "image" => "images/jersey4.jpg"
    )
);

?>

<!DOCTYPE html>
<html>

<head>

    <title><?php echo $shopName; ?> - Football Jerseys</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <!-- Header -->

    <header>

        <div class="logo">
                <a href="index.php">
                    <img src="img/logo.png.jpeg" alt="RG Retro Logo">
                </a>
            </div>

            <form class="search-box" action="index.php" method="get">

                <input type="text" name="search" placeholder="Search jerseys...">

                <button type="submit">Search</button>

            </form>

            <div class="header-links">

                <a href="pages/login.php">Login</a>

                <a href="pages/cart.php">Cart</a>

            </div>

        </div>

        <nav>

            <a href="index.php">Home</a>

            <a href="#categories">Categories</a>

            <a href="#products">Jerseys</a>

            <a href="pages/contact.php">Contact</a>

        </nav>

    </header>


    <!-- Welcome Section -->

    <section class="hero">

        <div class="hero-content">

            <h1>Football Jerseys for Every Fan</h1>

            <p>
                Find your favourite club and player jerseys
                at RG Retro.
            </p>

            <a href="#products" class="shop-button">
                Shop Jerseys
            </a>

        </div>

    </section>


    <!-- Categories -->

    <section class="categories" id="categories">

        <h2>Shop By Category</h2>

        <p class="section-text">
            Choose from different types of football jerseys.
        </p>

        <div class="category-container">

            <?php foreach ($categories as $category) { ?>

                <div class="category-card">

                    <h3><?php echo $category; ?></h3>

                    <a href="#products">View Jerseys</a>

                </div>

            <?php } ?>

        </div>

    </section>


    <!-- Products -->

    <section class="products" id="products">

        <h2>Featured Jerseys</h2>

        <p class="section-text">
            Check out some of our popular football jerseys.
        </p>

        <div class="product-container">

            <?php foreach ($products as $product) { ?>

                <div class="product-card">

                    <div class="product-image">

                        <img
                            src="<?php echo $product['image']; ?>"
                            alt="<?php echo $product['name']; ?>"
                        >

                    </div>

                    <div class="product-details">

                        <p class="edition">
                            <?php echo $product['edition']; ?>
                        </p>

                        <h3>
                            <?php echo $product['name']; ?>
                        </h3>

                        <p class="price">
                            <?php echo $product['price']; ?>
                        </p>

                        <p class="sizes">
                            Sizes: S, M, L, XL, XXL
                        </p>

                        <a href="pages/product.php" class="view-button">
                            View Details
                        </a>

                    </div>

                </div>

            <?php } ?>

        </div>

    </section>


    <!-- About Store -->

    <section class="about">

        <h2>Why Choose RG Retro?</h2>

        <div class="about-container">

            <div class="about-box">

                <h3>Quality Jerseys</h3>

                <p>
                    We provide football jerseys in different
                    editions and sizes.
                </p>

            </div>

            <div class="about-box">

                <h3>Different Clubs</h3>

                <p>
                    Choose jerseys from popular football clubs
                    and national teams.
                </p>

            </div>

            <div class="about-box">

                <h3>Easy Ordering</h3>

                <p>
                    Select your favourite jersey, add it to
                    your cart and place your order easily.
                </p>

            </div>

        </div>

    </section>


    <!-- Footer -->

    <footer>

        <div class="footer-content">

            <div>

                <h3><?php echo $shopName; ?></h3>

                <p>
                    Your local football jersey store.
                </p>

            </div>

            <div>

                <h3>Quick Links</h3>

                <a href="index.php">Home</a>
                <a href="#products">Jerseys</a>
                <a href="pages/contact.php">Contact</a>

            </div>

            <div>

                <h3>Contact</h3>

                <p>Phone: 8733934969</p>

                <p>Email: rgretro43@gmail.com</p>

            </div>

        </div>

        <div class="copyright">

            <p>
                &copy; <?php echo date("Y"); ?> RG Retro. All Rights Reserved.
            </p>

        </div>

    </footer>

</body>

</html>