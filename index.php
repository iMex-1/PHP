<?php
include 'db.php';
$result = mysqli_query($conn, "SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students Liste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <h2>Students List</h2>
    <table class="table table-bordered text-center">
        <tr>
            <th>CEF</th><th>Image</th><th>FullName</th><th>Email</th><th>GitHub</th><th>Filiere</th><th>Gender</th><th>Loisirs</th><th>Actions</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['cef']; ?></td>
            <td><img src="<?= $row['image']; ?>" height="90" width="90" style="border-radius: 50%;"></td>
            <td><?= $row['fname']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><a href="<?= $row['giturl']; ?>" class="btn btn-success"><i class="fa-brands fa-github"></i> Github</a></td>
            <td><?= $row['filier']; ?></td>
            <td><?= $row['gender']; ?></td>
            <td><?= $row['losirs']; ?></td>
            <td>
                <a href="edit.php?cef=<?= $row['cef']; ?>" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                <a href="delete.php?cef=<?= $row['cef']; ?>" onclick="return confirm('Supprimer ?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
