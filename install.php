<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db_server = trim($_POST['db_server']);
    $db_username = trim($_POST['db_username']);
    $db_password = trim($_POST['db_password']);
    $db_name = trim($_POST['db_name']);
    
    // First test the connection without database
    $test_conn = @mysqli_connect($db_server, $db_username, $db_password);
    
    if (!$test_conn) {
        $error = "Connection failed: " . mysqli_connect_error();
    } else {
        // Create constants.php content
        $config_content = "<?php\n";
        $config_content .= "// Database configuration\n";
        $config_content .= "define('DB_SERVER', '" . addslashes($db_server) . "');\n";
        $config_content .= "define('DB_USERNAME', '" . addslashes($db_username) . "');\n";
        $config_content .= "define('DB_PASSWORD', '" . addslashes($db_password) . "');\n";
        $config_content .= "define('DB_NAME', '" . addslashes($db_name) . "');\n";
        $config_content .= "?>";
        
        // Write to constants.php
        if (file_put_contents('constants.php', $config_content)) {
            // Create database
            $sql = "CREATE DATABASE IF NOT EXISTS `" . mysqli_real_escape_string($test_conn, $db_name) . "`";
            if (mysqli_query($test_conn, $sql)) {
                $success[] = "Database created successfully or already exists.";
                
                // Select the database
                mysqli_select_db($test_conn, $db_name);
                
                // Create users table
                $sql = "CREATE TABLE IF NOT EXISTS users (
                    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    username VARCHAR(50) NOT NULL UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
                
                if (mysqli_query($test_conn, $sql)) {
                    $success[] = "Users table created successfully.";
                    
                    // Create admin user
                    $admin_username = "admin";
                    $admin_password = password_hash("admin123", PASSWORD_DEFAULT);
                    
                    $check_admin = mysqli_query($test_conn, "SELECT id FROM users WHERE username = 'admin'");
                    
                    if (mysqli_num_rows($check_admin) == 0) {
                        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                        if ($stmt = mysqli_prepare($test_conn, $sql)) {
                            mysqli_stmt_bind_param($stmt, "ss", $admin_username, $admin_password);
                            if (mysqli_stmt_execute($stmt)) {
                                $success[] = "Admin user created successfully.";
                                $success[] = "Username: admin";
                                $success[] = "Password: admin123";
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }
                } else {
                    $error = "Error creating table: " . mysqli_error($test_conn);
                }
            } else {
                $error = "Error creating database: " . mysqli_error($test_conn);
            }
            mysqli_close($test_conn);
        } else {
            $error = "Could not write configuration file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success {
            color: #28a745;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .error {
            color: #dc3545;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Database Configuration</h1>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <?php foreach($success as $message): ?>
                <div class="success"><?php echo $message; ?></div>
            <?php endforeach; ?>
            <a href="index.php" class="button">Go to Login Page</a>
        <?php else: ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="db_server">Database Server:</label>
                    <input type="text" id="db_server" name="db_server" value="localhost" required>
                </div>
                
                <div class="form-group">
                    <label for="db_username">Database Username:</label>
                    <input type="text" id="db_username" name="db_username" value="root" required>
                </div>
                
                <div class="form-group">
                    <label for="db_password">Database Password:</label>
                    <input type="password" id="db_password" name="db_password">
                </div>
                
                <div class="form-group">
                    <label for="db_name">Database Name:</label>
                    <input type="text" id="db_name" name="db_name" value="learn" required>
                </div>
                
                <button type="submit">Install</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html> 