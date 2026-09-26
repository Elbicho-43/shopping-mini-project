<?php
session_start();

include "../../includes/db.php";

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $adminQuery = mysqli_query($conn, "SELECT * FROM admins WHERE username = '$username'");

    if (mysqli_num_rows($adminQuery) > 0) {

        $admin = mysqli_fetch_assoc($adminQuery);

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $errorMessage = "Incorrect password.";
        }

    } else {
        $errorMessage = "No admin account found with that username.";
    }

}
?>
<!DOCTYPE html>
<html>
<head>
    <title>RG Retro - Admin Login</title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>

    <section class="contact-section" style="min-height:100vh; display:flex; align-items:center; justify-content:center; flex-direction:column;">

        <h2>Admin Login</h2>
        <p class="section-text">RG Retro Management Panel</p>

        <div class="auth-box">

            <?php if ($errorMessage != "") { ?>
                <p class="form-error"><?php echo $errorMessage; ?></p>
            <?php } ?>

            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="shop-button">Login</button>
            </form>

        </div>

    </section>

</body>
</html>