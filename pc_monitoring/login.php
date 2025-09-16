<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];

    if ($type === 'user') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = 'user';
                header('Location: user_dashboard.html');
                exit;
            } else {
                $_SESSION['error'] = 'Invalid email or password.';
                header('Location: login.html');
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Login failed: ' . $e->getMessage();
            header('Location: login.html');
            exit;
        }
    } elseif ($type === 'admin') {
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare('SELECT id, password FROM admins WHERE username = ?');
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['user_type'] = 'admin';
                header('Location: admin_dashboard.html');
                exit;
            } else {
                $_SESSION['error'] = 'Invalid username or password.';
                header('Location: login.html');
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Login failed: ' . $e->getMessage();
            header('Location: login.html');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Invalid login type.';
        header('Location: login.html');
        exit;
    }
} else {
    header('Location: login.html');
    exit;
}
?>