<?php
session_start();
$error = "";

try
{
    $pdo = new PDO('mysql:host=localhost;port=3307;dbname=lasalle_db;', 'user', 'user', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        ]);

    if(isset($_POST["login"]))
    {
        if(empty($_POST["email"]) || empty($_POST["password"]))
        {
            $error = '<label>Please enter your credentials</label>';
        }
        else
        {
            $query = "SELECT * FROM students WHERE email = :email AND password = :password";
            $stmt = $pdo->prepare($query);
            $stmt->execute(
                array(
                    'email'     =>     $_POST["email"],
                    'password'     =>     $_POST["password"]
                )
            );
            $count = $stmt->rowCount();
            if($count > 0)
            {
                $_SESSION["email"] = $_POST["email"];
                header("location:login_success.php");
            }
            else
            {
                $error = '<label>Invalid credentials!</label>';
            }
        }
    }
}
catch(PDOException $error)
{
    $error = $error->getMessage();
}
?>

<?php include 'includes/header.php';?>

<head>
    <link href="css/style.css" rel="stylesheet">
    <title>Login</title>
    <br/>
</head>
<body>

    <form id="login" class="login" method="post" >
        <h3>Login</h3>
        <br/>
        <label>
            <input type="text" name="email" placeholder="Email">
        </label>
        <label>
            <input type="password" name="password" placeholder="Password">
        </label>
        <br>
        <input type="submit" name="login" value="Login">
        <span class="error"><?= $error ?? "" ?></span>
    </form>

</body>




<?php
//if(isset($error))
//{
//    echo '<label id="error" class="error">'.$error.'</label>';
//}
//?>
