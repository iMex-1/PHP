<?php
include 'db.php';
include 'functions.php';

$cef = $_GET['cef'];
$student = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM students WHERE cef=$cef"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cef = $_POST['cef'];
    $name = $_POST['fname'];
    $giturl = $_POST['giturl'];
    $filier = $_POST['filier'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $hobbies = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : '';
    
    if (!empty($_FILES['image']['name'])) {
        $imagePath = uploadImage($_FILES['image']);
    } else {
        $imagePath = $student['image'];
    }
    $errors = '';

    if (empty($cef) || !preg_match('/^\d+$/', $cef)) {
        $errors .= "CEF is required and must be numeric.<br>";
    }

    if (empty($name) || !preg_match('/^[a-zA-Z\s]{3,}$/', $name)) {
        $errors .= "Fullname is required and must be at least 3 characters long and contain only letters and spaces.<br>";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors .= "A valid email is required.<br>";
    }

    if (empty($giturl) || !filter_var($giturl, FILTER_VALIDATE_URL)) {
        $errors .= "A valid GitHub URL is required.<br>";
    }

    $validFilieres = ['Informatique', 'Mathématiques', 'Physique', 'Chimie'];
    if (empty($filier) || !in_array($filier, $validFilieres)) {
        $errors .= "Filière is required and must be valid.<br>";
    }

    $validGenders = ['Male', 'Female'];
    if (empty($gender) || !in_array($gender, $validGenders)) {
        $errors .= "Gender is required and must be valid.<br>";
    }

    if (!$imagePath) {
        $errors .= "Image upload failed.<br>";
    }

    if (!empty($errors)) {
        echo "<div class='alert alert-danger'>$errors</div>";
        exit;
    }


    mysqli_query($conn, "UPDATE students SET fname='$name' ,email='$email' ,giturl='$giturl',filier='$filier',image ='$imagePath',gender='$gender' ,losirs='$hobbies' WHERE cef=$cef");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Modifier l'utilisateur</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>CEF</label>
            <input type="text" name="cef" class="form-control" required value="<?= $student['cef']; ?>" readonly>
        </div>
        <div class="mb-3">
            <label>Fullname</label>
            <input type="text" name="fname" class="form-control" required value="<?= $student['fname']; ?>">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required value="<?= $student['email']; ?>">
        </div>
        <div>
            <label>GitHub</label>
            <input type="url" name="giturl" class="form-control" required value="<?= $student['giturl']; ?>">
        </div>
        <div class="mb-3">
            <label>Filière</label>
            <select name="filier" class="form-control" required>
                <?php 
                $filieres = ['Informatique', 'Mathématiques', 'Physique', 'Chimie'];
                foreach ($filieres as $f) {
                    $selected = ($student['filier'] == $f) ? 'selected' : '';
                    echo "<option value='$f' $selected>$f</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control"  value="<?= $student['image']; ?>">
        </div>
        <div class="mb-3">
            <label>Genre</label><br>
            <input type="radio" name="gender" value="Male" required <?= $student['gender'] == 'Male' ? 'checked' : '' ?>> Male
            <input type="radio" name="gender" value="Female" required <?= $student['gender'] == 'Female' ? 'checked' : '' ?>> Female
        </div>

        <div class="mb-3">
            <label>Loisirs</label><br>
            <?php 
            $allHobbies = ['Sport', 'Lecture', 'Musique', 'Voyage'];
            $studentHobbies = explode(', ', $student['losirs']);
            foreach ($allHobbies as $hobby) {
                $checked = in_array($hobby, $studentHobbies) ? 'checked' : '';
                echo "<input type='checkbox' name='hobbies[]' value='$hobby' $checked> $hobby ";
            }
            ?>
        </div>

        <button type="submit" class="btn btn-success"><i class="fa-solid fa-pen"></i> Update</button>
    </form>
</div>
</body>
</html>
