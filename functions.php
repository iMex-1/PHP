<?php
function uploadImage($file) {
    $targetDir = "uploads/";
    $imageName = basename($file["name"]);
    $targetFile = $targetDir . uniqid() . "_" . $imageName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowed)) {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        }
    }
    return null;
}
?>
