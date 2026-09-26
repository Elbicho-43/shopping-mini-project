<?php

include "db.php";

$shopName = "RG Retro";

$categories = array();
$catResult = mysqli_query($conn, "SELECT * FROM categories");

while ($row = mysqli_fetch_assoc($catResult)) {
    $categories[] = array(
        "name" => $row['name'],
        "image" => $row['image']
    );
}

$products = array();
$prodResult = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC");

while ($row = mysqli_fetch_assoc($prodResult)) {

    $originalPrice = $row['price_value'];
    $discount = $row['discount_percent'];
    $finalPrice = $discount > 0 ? round($originalPrice - ($originalPrice * $discount / 100)) : $originalPrice;

    $products[] = array(
        "id" => $row['id'],
        "name" => $row['name'],
        "edition" => $row['edition'],
        "category" => $row['category'],
        "price" => "₹" . $finalPrice,
        "price_value" => $finalPrice,
        "original_price_value" => $originalPrice,
        "discount_percent" => $discount,
        "image" => $row['image'],
        "back_image" => $row['back_image'],
        "logo_image" => $row['logo_image'],
        "badge" => $row['badge'],
        "featured" => $row['featured'] == 1 ? true : false,
        "description" => $row['description'],
        "stock" => $row['stock']
    );
}

?>