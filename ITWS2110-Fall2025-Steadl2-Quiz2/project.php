<?php
require_once('db_config.php');

if (!isset($_SESSION['userId'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';
$newProjectId = null;

$conn = getDBConnection();
$usersResult = $conn->query("SELECT userId, firstName, lastName, nickName FROM users ORDER BY firstName, lastName");
$allUsers = $usersResult->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectName = trim($_POST['projectName'] ?? '');
    $projectDescription = trim($_POST['projectDescription'] ?? '');
    $selectedMembers = $_POST['members'] ?? [];
    
    if (empty($projectName)) {
        $error = 'Project name is required.';
    } elseif (empty($projectDescription)) {
        $error = 'Project description is required.';
    } elseif (count($selectedMembers) < 3) {
        $error = 'Please select at least 3 project members.';
    } else {
        $checkStmt = $conn->prepare("SELECT projectId FROM projects WHERE name = ?");
        $checkStmt->bind_param("s", $projectName);
        $checkStmt->execute();
        
        if ($checkStmt->get_result()->num_rows > 0) {
            $error = 'A project with this name already exists. Please choose a different name.';
        } else {
            $stmt = $conn->prepare("INSERT INTO projects (name, description) VALUES (?, ?)");
            $stmt->bind_param("ss", $projectName, $projectDescription);
            $stmt->execute();
            $newProjectId = $stmt->insert_id;
            
            $memberStmt = $conn->prepare("INSERT INTO projectMembership (projectId, memberId) VALUES (?, ?)");
            foreach ($selectedMembers as $memberId) {
                $memberStmt->bind_param("ii", $newProjectId, $memberId);
                $memberStmt->execute();
            }
            
            $success = 'Project created successfully!';
        }
    }
}

$projectsQuery = "
    SELECT p.projectId, p.name, p.description, p.createdAt,
           GROUP_CONCAT(CONCAT(u.firstName, ' ', u.lastName) SEPARATOR ', ') as members
    FROM projects p
    LEFT JOIN projectMembership pm ON p.projectId = pm.projectId
    LEFT JOIN users u ON pm.memberId = u.userId
    GROUP BY p.projectId
    ORDER BY p.createdAt DESC
";
$allProjects = $conn->query($projectsQuery)->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Project - Project Management System</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="container">
        <div class="nav">
            <div class="nav-left">
                <h2>Add New Project</h2>
            </div>
            <div class="nav-right">
                <a href="index.php" class="btn btn-secondary">← Dashboard</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="project.php">
            <div class="form-group">
                <label for="projectName">Project Name <span class="required">*</span></label>
                <input type="text" id="projectName" name="projectName" required>
            </div>
            
            <div class="form-group">
                <label for="projectDescription">Project Description <span class="required">*</span></label>
                <textarea id="projectDescription" name="projectDescription" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Select Project Members <span class="required">*</span> (Minimum 3)</label>
                <div style="border: 2px solid #ddd; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto;">
                    <?php foreach ($allUsers as $user): ?>
                        <div style="margin-bottom: 8px;">
                            <label>
                                <input type="checkbox" name="members[]" value="<?php echo $user['userId']; ?>">
                                <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName'] . ' (' . $user['nickName'] . ')'); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <button type="submit" class="btn btn-block">Create Project</button>
        </form>
        
        <div style="margin-top: 40px; padding-top: 40px; border-top: 2px solid #eee;">
            <h2>All Projects (<?php echo count($allProjects); ?>)</h2>
            
            <?php foreach ($allProjects as $project): ?>
                <div class="card <?php echo ($newProjectId == $project['projectId']) ? 'project-highlight' : ''; ?>">
                    <h3 class="card-title">
                        <?php echo htmlspecialchars($project['name']); ?>
                        <?php if ($newProjectId == $project['projectId']): ?>
                            <span style="background: #4ade80; color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; margin-left: 10px;">NEW</span>
                        <?php endif; ?>
                    </h3>
                    <p class="card-text"><?php echo htmlspecialchars($project['description']); ?></p>
                    <p class="card-text"><strong>Team Members:</strong> <?php echo htmlspecialchars($project['members']); ?></p>
                    <p style="color: #999; font-size: 14px; margin-top: 10px;">
                        Created: <?php echo date('M j, Y', strtotime($project['createdAt'])); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>