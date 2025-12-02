<?php
require_once('db_config.php');

if (isset($_SESSION['userId'])) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';
$formData = [
    'firstName' => '',
    'lastName' => '',
    'nickName' => $_SESSION['register_nickName'] ?? '',
    'password' => '',
    'confirmPassword' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['firstName'] = trim($_POST['firstName'] ?? '');
    $formData['lastName'] = trim($_POST['lastName'] ?? '');
    $formData['nickName'] = trim($_POST['nickName'] ?? '');
    $formData['password'] = $_POST['password'] ?? '';
    $formData['confirmPassword'] = $_POST['confirmPassword'] ?? '';
    
    $errors = [];
    
    if (empty($formData['firstName'])) {
        $errors[] = 'First name is required.';
    }
    
    if (empty($formData['lastName'])) {
        $errors[] = 'Last name is required.';
    }
    
    if (empty($formData['nickName'])) {
        $errors[] = 'Username (nickname) is required.';
    } elseif (strlen($formData['nickName']) < 3) {
        $errors[] = 'Username must be at least 3 characters long.';
    }
    
    if (empty($formData['password'])) {
        $errors[] = 'Password is required.';
    } elseif (strlen($formData['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    
    if ($formData['password'] !== $formData['confirmPassword']) {
        $errors[] = 'Passwords do not match.';
    }
    
    if (empty($errors)) {
        $conn = getDBConnection();
        
        $stmt = $conn->prepare("SELECT userId FROM users WHERE nickName = ?");
        $stmt->bind_param("s", $formData['nickName']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = 'Username already exists. Please choose a different username.';
            $stmt->close();
        } else {
            $stmt->close();
            
            $salt = bin2hex(random_bytes(16));
            
            $hashedPassword = hash('sha256', $formData['password'] . $salt);
            
            $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, nickName, passwordHash, passwordSalt) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", 
                $formData['firstName'], 
                $formData['lastName'], 
                $formData['nickName'], 
                $hashedPassword, 
                $salt
            );
            
            if ($stmt->execute()) {
                $newUserId = $stmt->insert_id;
                
                $_SESSION['userId'] = $newUserId;
                $_SESSION['firstName'] = $formData['firstName'];
                $_SESSION['lastName'] = $formData['lastName'];
                $_SESSION['nickName'] = $formData['nickName'];
                
                unset($_SESSION['register_nickName']);
                
                $stmt->close();
                closeDBConnection($conn);
                
                header('Location: index.php');
                exit();
            } else {
                $errors[] = 'Registration failed. Please try again.';
                $stmt->close();
            }
        }
        
        closeDBConnection($conn);
    }
    
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    }
}

// Clear the session variable after using it
if (isset($_SESSION['register_nickName'])) {
    unset($_SESSION['register_nickName']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Project Management System</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="register-container">
        <h1>Create Account</h1>
        <p class="subtitle">Join the Project Management System</p>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="register.php">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="firstName" 
                        name="firstName" 
                        value="<?php echo htmlspecialchars($formData['firstName']); ?>"
                        required 
                        autofocus
                    >
                </div>
                
                <div class="form-group">
                    <label for="lastName">Last Name <span class="required">*</span></label>
                    <input 
                        type="text" 
                        id="lastName" 
                        name="lastName" 
                        value="<?php echo htmlspecialchars($formData['lastName']); ?>"
                        required
                    >
                </div>
            </div>
            
            <div class="form-group">
                <label for="nickName">Username (Nickname) <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="nickName" 
                    name="nickName" 
                    value="<?php echo htmlspecialchars($formData['nickName']); ?>"
                    required
                    minlength="3"
                >
                <p class="password-hint">Must be at least 3 characters and unique</p>
            </div>
            
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    minlength="6"
                >
                <p class="password-hint">Must be at least 6 characters long</p>
            </div>
            
            <div class="form-group">
                <label for="confirmPassword">Confirm Password <span class="required">*</span></label>
                <input 
                    type="password" 
                    id="confirmPassword" 
                    name="confirmPassword" 
                    required
                    minlength="6"
                >
            </div>
            
            <button type="submit" class="btn btn-block">Create Account</button>
        </form>
        
        <div class="back-link">
            <a href="login.php">← Back to Login</a>
        </div>
    </div>
</body>
</html>