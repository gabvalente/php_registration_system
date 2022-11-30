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
$records_per_page = 100;

// Prepare the SQL statement and get records from our programs table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM  programs ORDER BY ID LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Fetch the records, so we can display them in our template.
$programs = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Get the total number of programs, this is so we can determine whether there should be a next and previous button
$num_programs = $pdo->query('SELECT COUNT(*) FROM programs')->fetchColumn();
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
    <h2>PROGRAMS</h2>
    <a href="create_programs.php" class="add">Add a new Program</a>
    <table>
        <thead>
        <tr>
            <th>Program ID</th>
            <th>Program type</th>
            <th>Program name</th>
            <td></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($programs as $program): ?>
            <tr>
                <td><?=$program['id']?></td>
                <td><?=$program['programType']?></td>
                <td><?=$program['programName']?></td>
                <td class="actions">
                    <a href="remove_programs.php?id=<?=$program['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="read_programs.php"><i class="fas fa-angle-double-left fa-sm"></i></a>
        <?php endif; ?>
        <?php if ($page*$records_per_page < $num_programs): ?>
            <a href="read_programs.php"><i class="fas fa-angle-double-right fa-sm"></i></a>
        <?php endif; ?>
    </div>
</div>

<?=template_footer()?>



