
<?php
session_start();

include 'includes/header.php';

$pdo = new PDO('mysql:host=localhost;port=3307;dbname=lasalle_db;', 'user', 'user', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,]);


if(isset($_SESSION["email"])){

    $email = $_SESSION["email"];

    $query = "SELECT firstName FROM students WHERE email = :email";
    $statement = $pdo->prepare($query);
    $statement->bindParam(":email", $email, PDO::PARAM_STR);
    $statement->execute();
    $name = $statement->fetchColumn();

    echo '<h3>Succefully logged in!<br/> Welcome, '.$name.'.</h3>';
}

else
{
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html>
<style>
    table, th, td {
        border:1px solid black;margin-left: auto; margin-right: auto; alignment: center;
    }
</style>
<head>
    <title>Account</title>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h3>STUDENT INFORMATION</h3>
    </div>
    <div class="card-body">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Birthday</th>
                <th>Email</th>
                <th>Phone number</th>
                <th>Language</th>
                <th>Campus</th>
                <th>Program name</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $query = "SELECT * FROM students WHERE email = :email";
            $statement = $pdo->prepare($query);
            $statement->bindParam(":email", $email, PDO::PARAM_STR);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_OBJ); //PDO::FETCH_ASSOC
            $result = $statement->fetchAll();
            if($result)
            {
                foreach($result as $row)
                {
                    ?>
                    <tr>
                        <td><?= $row->id; ?></td>
                        <td><?= $row->firstName; ?></td>
                        <td><?= $row->lastName; ?></td>
                        <td><?= $row->birthday; ?></td>
                        <td><?= $row->email; ?></td>
                        <td><?= $row->phoneNumber; ?></td>
                        <td><?= $row->language; ?></td>
                        <td><?= $row->campus; ?></td>
                        <td><?= $row->programName; ?></td>
                    </tr>
                    <?php
                }
            }
            else
            {
                ?>
                <tr>
                    <td colspan="5">No Record Found</td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div> </div>
</body>
</html>






<!--$query = "SELECT * FROM students";-->
<!--$stmt = $pdo->prepare($query);-->
<!--$stmt->execute();-->
<!---->
<!--$student = $stmt->fetchAll();-->
<!---->
<!---->
<!--if(isset($_SESSION["email"]))-->