<?php


require_once 'constants.php';

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if(!$conn) {
    echo "<script>alert('Database connection failed. Redirecting to installation page...');window.location.href = 'install.php';</script>";
    exit();
}

?> 