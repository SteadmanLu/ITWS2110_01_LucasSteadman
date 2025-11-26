<?php
require_once('db_config.php');

// Check if user is logged in
if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

// Get user information from session
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$nickName = $_SESSION['nickName'];
$userId = $_SESSION['userId'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            display: block;
        }
        
        .welcome-section {
            text-align: center;
            padding: 40px 0;
            border-bottom: 2px solid #eee;
            margin-bottom: 40px;
        }
        
        .welcome-section h1 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .welcome-section p {
            color: #666;
            font-size: 18px;
        }
        
        .user-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <div class="nav-left">
                <h2>Project Management System</h2>
            </div>
            <div class="nav-right">
                <span class="user-info">Logged in as: <strong><?php echo htmlspecialchars($nickName); ?></strong></span>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <div class="welcome-section">
            <h1>Welcome, <?php echo htmlspecialchars($firstName); ?>! 👋</h1>
            <p>What would you like to do today?</p>
            <span class="user-badge"><?php echo htmlspecialchars($firstName . ' ' . $lastName); ?></span>
        </div>
        
        <div class="dashboard-grid">
            <a href="project.php" class="dashboard-card">
                <h2>Add a Project</h2>
                <p>Create a new project and assign team members</p>
            </a>
            
            <a href="project.php" class="dashboard-card">
                <h2>View Projects</h2>
                <p>See all existing projects and their members</p>
            </a>
        </div>
    </div>
</body>
</html>