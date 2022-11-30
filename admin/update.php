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

// Check if the contact id exists, for example update.php?id=1 will get the contact with the id of 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create_programs.php, but instead we update a record and not insert
        
        $id = $_POST['id'] ?? NULL;
        $fName = $_POST['fName'] ?? '';
        $lName = $_POST['lName'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $email = $_POST['email'] ?? '';
        $pw = $_POST['pw'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $lang = $_POST['lang'] ?? '';
        $campus = $_POST['campus'] ?? '';
        $pType = $_POST['pType'] ?? '';
        $programName = $_POST['programName'] ?? '';
   
        
        // Update the record
        $stmt = $pdo->prepare('UPDATE students SET firstName = ?, lastName = ?, birthday = ?, email = ?, phoneNumber = ?, language = ?, campus = ?, password = ?, programName = ? WHERE id = ?');
        $stmt->execute([ $fName, $lName, $dob, $email, $pw, $phone, $lang, $campus, $programName, $_GET['id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the students table
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$student) {
        exit('No student found with this ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header('Read')?>

    <div class="content update">
        <h2>Update Student #<?=$student['id']?></h2>
        <form action="update.php?id=<?=$student['id']?>" method="post">
            <label for="fName">First Name</label>
            <label for="lName">Last Name</label>
            <input type="text" name="fName" placeholder="first name" value="<?=$student['firstName']?>" id="fName">
            <input type="text" name="lName" placeholder="last name" value="<?=$student['lastName']?>" id="lName">
            <label for="dob">Date of birth</label>
            <label for="email">Email</label>
            <input type="text" name="dob" placeholder="dd-mm-yyyy" value="<?=$student['birthday']?>" id="dob">
            <input type="text" name="email" placeholder="student@email.com" value="<?=$student['email']?>" id="email">
            <label for="phone">Phone number</label>
            <label for="lang">Language</label>
            <input type="text" name="phone" placeholder="xxxxxxxxx" value="<?=$student['phoneNumber']?>" id="phone">
            <input type="ext" name="lang" placeholder="english/french" value="<?=$student['language']?>"  id="lang">
            <label for="campus">Campus</label>
            <label for="programName">Program Name</label>
            <input type="text" name="campus" placeholder="campus" value="<?=$student['campus']?>" id="campus">
            <input type="text" name="programName" value="<?=$student['programName']?>" id="programName">
            <input type="submit" value="Update">
        </form>
        <?php if ($msg): ?>
            <p><?=$msg?></p>
        <?php endif; ?>
    </div>

<?=template_footer()?>
