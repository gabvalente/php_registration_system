<?php
include 'functions.php';


$pdo = new PDO('mysql:host=localhost;port=3307;dbname=lasalle_db;', 'user', 'user', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,]);


$msg = '';
// Check that the program id exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM programs WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $program = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$program) {
        exit('program doesn\'t exist with that id!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM programs WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the program!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: read_programs.php');
            exit;
        }
    }
} else {
    exit('No id specified!');
}
?>

<?=template_header('Delete')?>

<div class="content delete">
    <h2>Delete program #<?=$program['id']?></h2>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php else: ?>
        <p>Are you sure you want to delete program #<?=$program['id']?>?</p>
        <div class="yesno">
            <a href="remove_programs.php?id=<?=$program['id']?>&confirm=yes">Yes</a>
            <a href="remove_programs.php?id=<?=$program['id']?>&confirm=no">No</a>
        </div>
    <?php endif; ?>
</div>

<?=template_footer()?>


