<!--
    This file is used to check if the admin's credentials are correct.
    It is used to check if the admin is logged in.
-->

<?php
include 'config.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Verify password 
        if ($password === $row['password']) {
            $_SESSION['admin_id'] = $row['admin_id'];
            $_SESSION['username'] = $row['username'];
            echo json_encode(['success' => true]);
            exit;
        }
    }
    
    echo json_encode(['success' => false]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Invalid request method']); 