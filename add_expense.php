<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? 'other';
    $amount = (float)($_POST['amount'] ?? 0);
    $expense_date = $_POST['expense_date'] ?? date('Y-m-d');

    $allowed = ['food', 'transport', 'shopping', 'bills', 'entertainment', 'other'];
    if (!in_array($category, $allowed, true)) {
        $category = 'other';
    }

    if ($title !== '' && $amount > 0) {
        $stmt = $pdo->prepare("INSERT INTO expenses (title, category, amount, expense_date) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $category, $amount, $expense_date]);
    }
}

header('Location: index.php');
exit;
