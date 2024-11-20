<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create uploads directory if it doesn't exist
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }
    
    $target_dir = "uploads/";
    $file_extension = strtolower(pathinfo($_FILES["profile_image"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension; // Generate unique filename
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is actual image
    $check = getimagesize($_FILES["profile_image"]["tmp_name"]);
    if($check === false) {
        header("Location: dashboard.php?upload=error");
        exit();
    }
    
    // Allow certain file formats
    if($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg" && $file_extension != "gif" ) {
        header("Location: dashboard.php?upload=error");
        exit();
    }
    
    // Upload file
    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
        // Update database with new image path
        $sql = "UPDATE users SET profile_image = ? WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $new_filename, $_SESSION["id"]);
            
            if(mysqli_stmt_execute($stmt)) {
                header("Location: dashboard.php?upload=success");
            } else {
                header("Location: dashboard.php?upload=error");
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        header("Location: dashboard.php?upload=error");
    }
}
?> 