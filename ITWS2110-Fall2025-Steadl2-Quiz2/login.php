<?php
require_once('db_config.php');

if (isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$loginAttempted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginAttempted = true;
    $nickName = trim($_POST['nickName'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($nickName) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $conn = getDBConnection();
        
        $stmt = $conn->prepare("SELECT userId, firstName, lastName, nickName, passwordHash, passwordSalt FROM users WHERE nickName = ?");
        $stmt->bind_param("s", $nickName);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashedPassword = hash('sha256', $password . $user['passwordSalt']);
            
            if ($hashedPassword === $user['passwordHash']) {
                $_SESSION['userId'] = $user['userId'];
                $_SESSION['firstName'] = $user['firstName'];
                $_SESSION['lastName'] = $user['lastName'];
                $_SESSION['nickName'] = $user['nickName'];
                
                $stmt->close();
                closeDBConnection($conn);
                
                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid username or password. Please try again.';
            }
        } else {
            $stmt->close();
            closeDBConnection($conn);
            
            $_SESSION['register_nickName'] = $nickName;
            header('Location: register.php');
            exit();
        }
        
        $stmt->close();
        closeDBConnection($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Project Management System</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="login-container">
        <h1>Welcome Back</h1>
        <p class="subtitle">Project Management System</p>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="nickName">Username (Nickname)</label>
                <input 
                    type="text" 
                    id="nickName" 
                    name="nickName" 
                    value="<?php echo htmlspecialchars($_POST['nickName'] ?? ''); ?>"
                    required 
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                >
            </div>
            
            <button type="submit" class="btn btn-block">Login</button>
        </form>
        
        <div class="info-box">
            <p><strong>New user?</strong> If your username is not found, you'll be automatically redirected to the registration page.</p>
        </div>
    </div>
</body>
</html>