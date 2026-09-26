<?php
session_start();

include "../../includes/db.php";
include "../../includes/admin-auth.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $orderId = (int)$_POST['order_id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = $orderId");

}

header("Location: dashboard.php");
exit;
?>