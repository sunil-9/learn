<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: index.php");
    exit();
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 20px auto;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 20px auto;
            display: block;
        }
        .upload-form {
            margin: 20px 0;
        }
        .upload-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .upload-btn:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            margin: 10px 0;
        }
        .success {
            color: #28a745;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Get user's profile image
        $sql = "SELECT profile_image FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        
        // Display profile image or placeholder
        $imageUrl = $user['profile_image'] ? 'uploads/' . $user['profile_image'] : 'https://via.placeholder.com/150';
        ?>
        
        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="Profile Image" class="profile-image">
        
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
        <p>Your account ID is: <?php echo htmlspecialchars($_SESSION["id"]); ?></p>
        
        <!-- Image Upload Form -->
        <form action="upload_profile.php" method="post" enctype="multipart/form-data" class="upload-form">
            <input type="file" name="profile_image" accept="image/*" required>
            <button type="submit" class="upload-btn">Upload Profile Picture</button>
        </form>
        
        <?php
        if (isset($_GET['upload'])) {
            if ($_GET['upload'] === 'success') {
                echo '<p class="success">Profile picture updated successfully!</p>';
            } else if ($_GET['upload'] === 'error') {
                echo '<p class="error">Error uploading profile picture. Please try again.</p>';
            }
        }
        ?>
        
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</body>
</html> 