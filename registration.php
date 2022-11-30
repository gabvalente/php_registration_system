
<?php
include 'includes/header.php';
//include 'db/db.php';

try {
    $pdo = new PDO('mysql:host=localhost;port=3307;dbname=lasalle_db;', 'user', 'user', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage());
}

$errors = [];
$inputs = [];

$inputs["fName"] = "";
$inputs["lName"] = "";
$inputs["dob"] = "";
$inputs["email"] = "";
$inputs["pw"] = "";
$inputs["phone"] = "";
$inputs["lang"] = "";
$inputs["campus"] = "";
$inputs["pType"] = "";
$inputs["programName"] = "";

function verify($input): string //rename
{
    return htmlspecialchars(stripslashes(trim($input)));
}

if (isset($_POST["submit"])) {

    if (empty($_POST["fName"])) {
        $errors["fName"] = "First name is required!";
    } else if (!preg_match("/^[A-Z][a-z]{2,15}$/", verify($_POST["fName"]))) {
        $errors["fName"] = "First name must start with an uppercase and contain 2-15 letters";
    } else {
        $inputs["fName"] = $_POST["fName"];
    }

    if (empty($_POST["lName"])) {
        $errors["lName"] = "Last name is required!";
    } else if (!preg_match("/^[A-Z][a-z]{2,20}$/", verify($_POST["lName"]))) {
            $errors["fName"] = "Last name must be 2-20 letters using A-z";
    } else {
        $inputs["lName"] = $_POST["lName"];
    }

    if (empty($_POST["dob"])) {
        $errors["dob"] = "Date of birth is required! (dd-mm-yyyy)";
    } else if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/", verify($_POST["dob"]))) {
           $errors["dob"] = "Date format should be: dd-mm-yyyy";
    } else {
        $inputs["dob"] = $_POST["dob"];
    }

    if (empty($_POST["email"])) {
        $errors["email"] = "Email is required!";
    } else if (!filter_var(verify($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Invalid email format";
    } else {
        $inputs["email"] = $_POST["email"];
    }

    if(empty($_POST['pw'])) {
        $errors['pw'] = "Password is required";
    } else if( ! preg_match("/^([a-zA-Z0-9!@#$%^&*?]{6,20})$/", verify($_POST['pw']))) {
        $errors['pw'] = "Invalid password format";
    } else {
        $inputs["pw"] = $_POST["pw"];
    }

    if (empty($_POST["phone"])) {
        $errors["phone"] = "Phone number is required!";
    } else if (!filter_var(verify($_POST["phone"]), FILTER_SANITIZE_NUMBER_INT)) {
        $errors["phone"] = "Invalid phone format";
    } else {
        $inputs["phone"] = $_POST["phone"];
    }

    if (empty($_POST["lang"])) {
        $errors["lang"] = "Please select a language!";
    } else {
        $inputs["lang"] = $_POST["lang"];
    }

    if (empty($_POST["campus"])) {
        $errors["campus"] = "Please select a campus!";
    } else {
        $inputs["campus"] = $_POST["campus"];
    }

    if (empty($_POST["pType"])) {
        $errors["pType"] = "Please select a Program type!";
    } else {
        $inputs["pType"] = $_POST["pType"];
    }

    if (empty($_POST["programName"])) {
        $errors["programName"] = "Please select a Program!";
    } else {
        $inputs["programName"] = $_POST["programName"];
    }

    if (empty($errors)){

            $stmt = $pdo->prepare('INSERT INTO students (firstName, lastName, birthday, email, phoneNumber, language, campus, programName, password) VALUES (?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$inputs['fName'], $inputs['lName'], $inputs['dob'], $inputs['email'], $inputs['phone'], $inputs['lang'], $inputs['campus'], $inputs['programName'], $inputs['pw']]);

    }
}

?>

<link rel="stylesheet" href="css/style.css" type="text/css">
<div class="container">
    <form id="contact" action="<?= $_SERVER['PHP_SELF']; ?>" method="post">
        <h3>LaSalle Registration</h3>
        <br>
        <fieldset>
            <label>
                <input placeholder="Your first name" type="text" tabindex="1" name="fName">
            </label>
            <span class="error"><?= $errors["fName"] ?? "" ?></span>
        </fieldset>
        <fieldset>
            <label>
                <input placeholder="Your last name" type="text" tabindex="2" name="lName" >
            </label>
            <span class="error"><?= $errors["lName"] ?? "" ?></span>
        </fieldset>
        <fieldset>
            <label>
                <input placeholder="Your date of birth (dd-mm-yyyy)" type="text" tabindex="3" name="dob" >
            </label>
            <span class="error"><?= $errors["dob"] ?? "" ?></span>
        </fieldset>

        <fieldset>
            <label>
                <input placeholder="Your email address" type="text" tabindex="4" name="email" >
            </label>
            <span class="error"><?= $errors["email"] ?? "" ?></span>
        </fieldset>
        <fieldset>
            <label>
                <input placeholder="Your password" type="text" tabindex="4" name="pw" >
            </label>
            <span class="error"><?= $errors["pw"] ?? "" ?></span>
        </fieldset>

        <fieldset>
            <label>
                <input placeholder="Your phone number" type="text" tabindex="5" name="phone" >
            </label>
            <span class="error"><?= $errors["phone"] ?? "" ?> </span>
        </fieldset>
        <fieldset>
            <label for="language">Language:</label>
            <label for="lang"></label><select name="lang" id="lang" >
                <option value="">--Select--</option>
                <option value="english">English</option>
                <option value="french">Français</option>
            </select>
            <span class="error"><?= $errors["lang"] ?? "" ?> </span>
        </fieldset>
        <fieldset>
            <label for="campus">Campus:</label>
            <select name="campus" id="campus">
                <option value="">--Select--</option>
                <option value="montreal">Montreal</option>
                <option value="laval">Laval</option>
            </select>
            <span class="error"><?= $errors["campus"] ?? "" ?></span>
        </fieldset>
        <fieldset>
            <label for="pType">Program Type:</label>
            <select name="pType" id="pType">
                <option value="">--Select--</option>
                <option value="aec">AEC</option>
                <option value="dep">DEP</option>
                <option value="dec">DEC</option>
                <option value="asp">ASP</option>
            </select>
            <span class="error"><?= $errors["pType"] ?? "" ?></span>
        </fieldset>
        <fieldset>

            <?php
            $stmt = $pdo->prepare('SELECT * FROM programs');
            $stmt->execute();
            $programs = $stmt->fetchAll();

            ?>

            <label for="programName">Program:</label>
            <select name="programName">
                <option value="">--Select--</option>-->
                <?php foreach($programs as $program){ ?>
                <option value="<?= $program["programName"] ?>"><?= $program["programName"] ?> </option>
                <?php } ?>
<!--            <select name="programName" id="programName">-->
<!--                <option value="">--Select--</option>-->
<!--                <option value="fashion, arts & design">Fashion, Arts & Design</option>-->
<!--                <option value="business & technologies">Business & Technologies</option>-->
<!--                <option value="social sciences & education">Social Sciences & Education</option>-->
<!--                <option value="vfx & game design">VFX & Game Design</option>-->
<!--                <option value="hotel management & tourism">Hotel Management & Tourism</option>-->
<!--            </select>-->
            <span class="error"><?= $errors["programName"] ?? "" ?></span>
            </select>

        </fieldset>
        <fieldset>
            <button name="reset" type="reset" id="registration-reset" class="myButtons">Reset</button>
        </fieldset>
        <fieldset>
            <button name="submit" type="submit" id="registration-submit" data-submit="...Sending" class="myButtons">Submit</button>
        </fieldset>
    </form>
</div>


