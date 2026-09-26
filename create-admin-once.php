<?php
include "includes/db.php";

$username = "admin";
$password = "admin123";

$hashed = password_hash($password, PASSWORD_DEFAULT);

$check = mysqli_query($conn, "SELECT id FROM admins WHERE username = '$username'");

if (mysqli_num_rows($check) > 0) {
    echo "An admin with that username already exists.";
} else {
    mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$username', '$hashed')");
    echo "Admin account created! Username: $username / Password: $password<br><br><strong>Now delete this file for security.</strong>";
}
?>