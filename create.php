<?php
include 'db.php';
include 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cef = $_POST['cef'];
    $name = $_POST['fname'];
    $giturl = $_POST['giturl'];
    $filier = $_POST['filier'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $hobbies = isset($_POST['hobbies']) ? implode(", ", $_POST['hobbies']) : '';
    $imagePath = uploadImage($_FILES['image']);
     
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

    $query = "INSERT INTO students (cef ,fname ,email ,giturl ,filier ,image ,gender ,losirs) VALUES ($cef,'$name','$email','$giturl','$filier','$imagePath','$gender','$hobbies')";
    if (!$imagePath) {
        echo "Erreur lors de l'upload de l'image.";
        exit;
    }
    mysqli_query($conn, $query);
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add a Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Add a Student</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>CEF</label>
            <input type="text" name="cef" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Fullname</label>
            <input type="text" name="fname" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div>
            <label>GitHub</label>
            <input type="url" name="giturl" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Filière</label>
            <select name="filier" class="form-control" required>
                <option value="Informatique">Informatique</option>
                <option value="Mathématiques">Mathématiques</option>
                <option value="Physique">Physique</option>
                <option value="Chimie">Chimie</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Genre</label><br>
            <input type="radio" name="gender" value="Male" required> Male
            <input type="radio" name="gender" value="Female" required> Female
        </div>
        <div class="mb-3">
            <label>Loisirs</label><br>
            <input type="checkbox" name="hobbies[]" value="Sport"> Sport
            <input type="checkbox" name="hobbies[]" value="Lecture"> Lecture
            <input type="checkbox" name="hobbies[]" value="Musique"> Musique
            <input type="checkbox" name="hobbies[]" value="Voyage"> Voyage
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Ajouter</button>
    </form>
</div>
</body>
</html>
