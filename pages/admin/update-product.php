<?php
session_start();

include "../../includes/db.php";
include "../../includes/admin-auth.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (int)$_POST['product_id'];
    $price = (int)$_POST['price_value'];
    $stock = (int)$_POST['stock'];
    $badge = mysqli_real_escape_string($conn, $_POST['badge']);
        $discountPercent = (int)$_POST['discount_percent'];
    $featured = isset($_POST['featured']) ? 1 : 0;

    
  mysqli_query($conn, "UPDATE products SET price_value = $price, stock = $stock, badge = '$badge', featured = $featured, discount_percent = $discountPercent WHERE id = $id");
   
}

header("Location: dashboard.php");
exit;
?>