<?php
session_start();
require_once 'config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if(empty($username) || empty($password) || empty($confirm_password)) {
        header("Location: index.php?error=empty_fields");
        exit();
    }
    if($password !== $confirm_password) {
        header("Location: index.php?error=password_mismatch");
        exit();
    }
    if(strlen($password) < 6) {
        header("Location: index.php?error=password_too_short");
        exit();
    }
    $sql = "SELECT id FROM users WHERE username = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) > 0) {
                header("Location: index.php?error=username_taken");
                exit();
            }
        } 
        mysqli_stmt_close($stmt);
    } 
    
    // Insert new user
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        mysqli_stmt_bind_param($stmt, "ss", $username, $hashed_password);
        
            
        if(mysqli_stmt_execute($stmt)) {
            header("Location: index.php?signup=success");
            mysqli_stmt_close($stmt);
            exit();
        } else {
            header("Location: index.php?error=registration_failed");
            mysqli_stmt_close($stmt);
            exit();
        }
    } 
}
mysqli_close($conn);
?> 