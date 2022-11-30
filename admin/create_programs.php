<?php
include 'functions.php';
try {
    $pdo = new PDO('mysql:host=localhost;port=3307;dbname=lasalle_db;', 'user', 'user', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage());
}

$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record

    // Set up the variables that are going to be inserted, we must check if the POST variables exist if not we can default them to blank
//    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;

    // Check if POST variable "name" exists, if not default the value to blank, basically the same for all variables
    $ID = $_POST['ID'] ?? '';
    $programType = $_POST['programType'] ?? '';
    $programName = $_POST['programName'] ?? '';

    // Insert new record into the programs table
    $stmt = $pdo->prepare('INSERT INTO programs VALUES (?, ?, ?)');
    $stmt->execute([$ID, $programType, $programName]);
    // Output message
    $msg = 'Created Successfully!';
}
?>


<?=template_header('Create')?>

<div class="content update">
    <h2>Add Program</h2>
    <form action="create_programs.php" method="post">
        <label for="id">Program Type</label>
        <label for="name">Program Name</label>
        <input type="text" name="programType" placeholder="Enter the type of the program" id="type">
        <input type="text" name="programName" placeholder="Enter the name of the program" id="name">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
