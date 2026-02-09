<?php
require_once __DIR__ . '/../../app/Config/Config.php';
require_once __DIR__ . '/../../app/Controllers/AuthController.php';

// Only allow POST requests (form submissions)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $auth->login($_POST['username'], $_POST['password'], $_POST['csrf_token']);
} else {
    // If someone tries to visit this page directly, send them back to login
    header("Location: " . BASE_URL . "/login");
    exit;
}
?>