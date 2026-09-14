<?php
session_start();

include "../includes/data.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $size = isset($_POST['size']) ? $_POST['size'] : 'M';

    $selectedProduct = null;

    foreach ($products as $product) {
        if ($product['id'] == $productId) {
            $selectedProduct = $product;
        }
    }

    if ($selectedProduct != null) {

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        $_SESSION['cart'][] = array(
            "id" => $selectedProduct['id'],
            "name" => $selectedProduct['name'],
            "price" => $selectedProduct['price_value'],
            "image" => $selectedProduct['image'],
            "size" => $size,
            "qty" => 1
        );

    }

}

header("Location: cart.php");
exit;
?>