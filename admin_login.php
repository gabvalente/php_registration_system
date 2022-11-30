<?php
ob_start();
session_start();
include 'includes/header.php';
$error = "";
?>
<html lang = "en">
<head>
    <title>Admin Login</title>
    <link href = "css/style.css" rel = "stylesheet">
</head>
<body>
<div class = "container form-signin">
    <?php
    $msg = '';
    if (isset($_POST['login'])) {
        if (empty($_POST["username"]) || empty($_POST["password"])) {
            $error = '<label>Please enter your credentials</label>';
        } else {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                if ($_POST['username'] == 'admin' &&
                    $_POST['password'] == '1234') {
                    $_SESSION['valid'] = true;
                    $_SESSION['timeout'] = time();
                    $_SESSION['username'] = 'admin';
                    header("location:admin/read.php");
                } else {
                    $error = '<label>Invalid credentials!</label>';
                }
            }
        }
    }
    ?>
</div>
<br>
<div class = "container">
    <form id="login" class="login" role = "form"
          action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method = "post">
        <h3>Admin Login</h3>
        <br>
        <input type = "text" class = "form-control"
               name = "username" placeholder = "username = admin"
               required autofocus></br>
        <input type = "password" class = "form-control"
               name = "password" placeholder = "password = 1234" required>
        <input type="submit" name="login" value="Login">
        <span class="error"><?= $error ?? "" ?></span>
    </form>
</div>
</body>
</html>