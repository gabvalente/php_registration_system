
<!DOCTYPE html>
<html>
<head>
    <link href="css/style_login.css" rel="stylesheet">
</head>
<body>

<ul>
    <li><a class="active" href="./registration.php">Registration</a></li>
    <li><a href="login_success.php">My Account</a></li>
    <li><?php if( isset($_SESSION['email']) && !empty($_SESSION['email']) )
        {
            ?>
            <a href="./logout.php">Logout</a>
        <?php }else{ ?>
            <a href="./login.php">Login</a>
        <?php } ?></li>
    <li><a href="admin_login.php">Admin</a></li>
</ul>

</body>
</html>
