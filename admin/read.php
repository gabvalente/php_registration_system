<?php
include 'functions.php';
// Connect to MySQL database
try {
    $pdo = new PDO('mysql:host=localhost;port=3307;dbname=lasalle_db;', 'user', 'user', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage());
}

// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 10;

// Prepare the SQL statement and get records from our students table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM  students ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records, so we can display them in our template.
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Get the total number of students, this is so we can determine whether there should be a next and previous button
$num_students = $pdo->query('SELECT COUNT(*) FROM students')->fetchColumn();
?>

<?=template_header('Read')?>


<!DOCTYPE html>
<html>
<style>
    table, th, td {
        border:1px solid black;margin-left: auto; margin-right: auto; alignment: center;
    }
</style>
<div class="content read">
    <h2>STUDENTS</h2>
    <a href="read_programs.php" class="add" id="x">Manage Programs</a>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>First name</th>
            <th>Last name</th>
            <th>Date of birth</th>
            <th>Email</th>
            <th>Phone number</th>
            <th>Language</th>
            <th>Campus</th>
            <th>Program name</th>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?=$student['id']?></td>
                <td><?=$student['firstName']?></td>
                <td><?=$student['lastName']?></td>
                <td><?=$student['birthday']?></td>
                <td><?=$student['email']?></td>
                <td><?=$student['phoneNumber']?></td>
                <td><?=$student['language']?></td>
                <td><?=$student['campus']?></td>
                <td><?=$student['programName']?></td>
                <td class="actions">
                    <a href="update.php?id=<?=$student['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$student['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page*$records_per_page < $num_students): ?>
            <a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>



