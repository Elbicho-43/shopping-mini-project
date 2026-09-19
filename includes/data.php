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
    $products[] = array(
        "id" => $row['id'],
        "name" => $row['name'],
        "edition" => $row['edition'],
        "category" => $row['category'],
        "price" => "₹" . $row['price_value'],
        "price_value" => $row['price_value'],
        "image" => $row['image'],
        "badge" => $row['badge'],
        "featured" => $row['featured'] == 1 ? true : false,
        "description" => $row['description']
    );
}

?>