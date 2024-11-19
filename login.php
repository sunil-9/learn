<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {    
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
    
    if(empty($username) || empty($password)) {
        header("Location: index.php?error=empty_fields");
        exit();
    }
       $sql = "SELECT id, username, password FROM users WHERE username = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                if(password_verify($password, $row['password'])) {
                    session_start();
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $row["id"];
                    $_SESSION["username"] = $row["username"];  
                    header("Location: dashboard.php");
                    exit();
                } else {
                    header("Location: index.php?error=invalid_password");
                    exit();
                }
            } else {
                header("Location: index.php?error=user_not_found");
                mysqli_stmt_close($stmt);
                exit();
            }
        } else {
            header("Location: index.php?error=database_error");
            mysqli_stmt_close($stmt);
            exit();
        }
        
    } 
    
    mysqli_close($conn);
} else {
    header("Location: index.php");
    exit();
}
?> 