<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $category = $_POST['category'] ?? 'other';
    $amount = (float)($_POST['amount'] ?? 0);
    $expense_date = $_POST['expense_date'] ?? date('Y-m-d');

    $allowed = ['food', 'transport', 'shopping', 'bills', 'entertainment', 'other'];
    if (!in_array($category, $allowed, true)) {
        $category = 'other';
    }

    if ($id > 0 && $title !== '' && $amount > 0) {
        $stmt = $pdo->prepare("UPDATE expenses SET title = ?, category = ?, amount = ?, expense_date = ? WHERE id = ?");
        $stmt->execute([$title, $category, $amount, $expense_date, $id]);
    }
}

header('Location: index.php');
exit;
