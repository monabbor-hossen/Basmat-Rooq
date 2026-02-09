<?php
require_once __DIR__ . '/../../app/Config/Config.php';
require_once __DIR__ . '/../../app/Controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $auth->login($_POST['username'], $_POST['password'], $_POST['csrf_token']);
} else {
    header("Location: " . BASE_URL . "/login");
    exit;
}
?>