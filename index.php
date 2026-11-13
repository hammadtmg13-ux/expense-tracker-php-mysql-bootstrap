<?php
require 'config.php';
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM expenses WHERE 1=1";
$params = [];

if ($filter !== 'all') {
    $sql .= " AND category = ?";
    $params[] = $filter;
}

if ($search !== '') {
    $sql .= " AND title LIKE ?";
    $params[] = '%' . $search . '%';
}

$sql .= " ORDER BY expense_date DESC, created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$expenses = $stmt->fetchAll();

$total = $pdo->query("SELECT COALESCE(SUM(amount), 0) AS total FROM expenses")->fetch();
$categoryTotals = $pdo->query("SELECT category, COALESCE(SUM(amount), 0) AS total FROM expenses GROUP BY category ORDER BY total DESC")->fetchAll();

function categoryBadge($category) {
    $map = [
        'food' => 'success',
        'transport' => 'primary',
        'shopping' => 'warning',
        'bills' => 'danger',
        'entertainment' => 'info',
        'other' => 'secondary',
    ];
    return $map[$category] ?? 'secondary';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SpendTrack - Simple Expense Tracker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="text-center mb-4">
        <h1 class="fw-bold">SpendTrack</h1>
        <p class="text-muted">A simple expense tracker built with PHP, MySQL and Bootstrap</p>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="card text-center shadow-sm total-card">
            <div class="card-body">
              <div class="text-muted small">Total spent</div>
              <div class="fs-3 fw-bold">Rs <?php echo number_format((float)$total['total'], 2); ?></div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="text-muted small mb-2">By category</div>
              <?php if (empty($categoryTotals)): ?>
                <p class="small text-muted mb-0">No expenses yet.</p>
              <?php else: ?>
                <?php foreach ($categoryTotals as $row): ?>
                  <div class="d-flex justify-content-between small">
                    <span><?php echo htmlspecialchars(ucfirst($row['category'])); ?></span>
                    <span class="fw-bold">Rs <?php echo number_format((float)$row['total'], 2); ?></span>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <form action="add_expense.php" method="POST" class="row g-2">
            <div class="col-md-4">
              <input type="text" name="title" class="form-control" placeholder="Expense title, for example Lunch" required maxlength="255">
            </div>
            <div class="col-md-2">
              <select name="category" class="form-select">
                <option value="food">Food</option>
                <option value="transport">Transport</option>
                <option value="shopping">Shopping</option>
                <option value="bills">Bills</option>
                <option value="entertainment">Entertainment</option>
                <option value="other" selected>Other</option>
              </select>
            </div>
            <div class="col-md-2">
              <input type="number" step="0.01" min="0.01" name="amount" class="form-control" placeholder="Amount" required>
            </div>
            <div class="col-md-2">
              <input type="date" name="expense_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="col-md-2 d-grid">
              <button type="submit" class="btn btn-primary">Add Expense</button>
            </div>
          </form>
        </div>
      </div>

      <form method="GET" class="row g-2 mb-3">
        <div class="col-md-6">
          <input type="search" name="search" value="<?php echo htmlspecialchars($search); ?>" class="form-control" placeholder="Search expenses">
        </div>
        <div class="col-md-6">
          <select name="filter" class="form-select" onchange="this.form.submit()">
            <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All categories</option>
            <option value="food" <?php echo $filter === 'food' ? 'selected' : ''; ?>>Food</option>
            <option value="transport" <?php echo $filter === 'transport' ? 'selected' : ''; ?>>Transport</option>
            <option value="shopping" <?php echo $filter === 'shopping' ? 'selected' : ''; ?>>Shopping</option>
            <option value="bills" <?php echo $filter === 'bills' ? 'selected' : ''; ?>>Bills</option>
            <option value="entertainment" <?php echo $filter === 'entertainment' ? 'selected' : ''; ?>>Entertainment</option>
            <option value="other" <?php echo $filter === 'other' ? 'selected' : ''; ?>>Other</option>
          </select>
        </div>
      </form>

      <?php if (empty($expenses)): ?>
        <div class="alert alert-info text-center">No expenses found. Add your first expense above.</div>
      <?php else: ?>
        <ul class="list-group shadow-sm" id="expenseList">
          <?php foreach ($expenses as $expense): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <span class="fw-semibold"><?php echo htmlspecialchars($expense['title']); ?></span>
                <div class="small text-muted">
                  <span class="badge bg-<?php echo categoryBadge($expense['category']); ?>"><?php echo htmlspecialchars($expense['category']); ?></span>
                  <?php echo htmlspecialchars($expense['expense_date']); ?>
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="fw-bold">Rs <?php echo number_format((float)$expense['amount'], 2); ?></span>
                <a href="delete_expense.php?id=<?php echo (int)$expense['id']; ?>" class="btn btn-sm btn-outline-danger delete-btn">Delete</a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <p class="text-center text-muted small mt-4">Built with HTML, CSS, JavaScript, Bootstrap, MySQL and PHP</p>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
