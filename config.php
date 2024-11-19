<?php
$current_page = basename($_SERVER['PHP_SELF']);
if($current_page === 'install.php') {
    return; 
}

// Check if constants.php exists
if (!file_exists('constants.php')) {
    echo "<script>alert('Database not configured. Redirecting to installation page...');";
    echo "window.location.href = 'install.php';</script>";
    exit();
}

require_once 'constants.php';

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if(!$conn) {
    echo "<script>alert('Database connection failed. Redirecting to installation page...');window.location.href = 'install.php';</script>";
    exit();
}

?> 