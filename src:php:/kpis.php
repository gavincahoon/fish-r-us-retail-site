<?php
$requiredRoles = ['admin'];
include 'checksession.php';
include 'db.php';
include 'navbar.php';

$start = isset($_GET['start']) && $_GET['start'] !== '' ? $_GET['start'] : '2000-01-01';
$end   = isset($_GET['end']) && $_GET['end'] !== '' ? $_GET['end']   : date('Y-m-d');

$params = [$start, $end];

// KPI queries within date range
$totalSalesStmt = $pdo->prepare("
  SELECT SUM(oi.price * oi.quantity)
  FROM order_items oi
  JOIN orders o ON oi.order_id = o.id
  WHERE o.created_at BETWEEN ? AND ?
");
$totalSalesStmt->execute($params);
$totalSales = $totalSalesStmt->fetchColumn() ?? 0;

$totalOrdersStmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE created_at BETWEEN ? AND ?");
$totalOrdersStmt->execute($params);
$totalOrders = $totalOrdersStmt->fetchColumn() ?? 0;

$topProductStmt = $pdo->prepare("\n  SELECT p.name, SUM(oi.quantity) AS total_sold\n  FROM order_items oi\n  JOIN products p ON oi.product_id = p.id\n  JOIN orders o  ON oi.order_id = o.id\n  WHERE o.created_at BETWEEN ? AND ?\n  GROUP BY oi.product_id\n  ORDER BY total_sold DESC\n  LIMIT 1\n");
$topProductStmt->execute($params);
$topProduct = $topProductStmt->fetch(PDO::FETCH_ASSOC) ?: ['name' => 'N/A', 'total_sold' => 0];

// Top 5 products
$topProductsStmt = $pdo->prepare("\n  SELECT p.name, SUM(oi.quantity) AS total_sold\n  FROM order_items oi\n  JOIN products p ON oi.product_id = p.id\n  JOIN orders o  ON oi.order_id = o.id\n  WHERE o.created_at BETWEEN ? AND ?\n  GROUP BY p.name\n  ORDER BY total_sold DESC\n  LIMIT 5\n");
$topProductsStmt->execute($params);
$topProducts = $topProductsStmt->fetchAll(PDO::FETCH_ASSOC);

// Sales by category
$categorySalesStmt = $pdo->prepare("\n  SELECT p.category_id, SUM(oi.quantity) AS total_sold\n  FROM order_items oi\n  JOIN products p ON oi.product_id = p.id\n  JOIN orders o  ON oi.order_id = o.id\n  WHERE o.created_at BETWEEN ? AND ?\n  GROUP BY p.category_id\n");
$categorySalesStmt->execute($params);
$categorySales = $categorySalesStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>KPI Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
  console.log('Top Product Labels:', <?= json_encode(array_column($topProducts ?? [], 'name')) ?>);
  console.log('Top Product Data:', <?= json_encode(array_column($topProducts ?? [], 'total_sold')) ?>);

console.log('Category Labels:', <?= json_encode(array_column($categorySales ?? [], 'category_id')) ?>);
console.log('Category Data:', <?= json_encode(array_column($categorySales ?? [], 'total_sold')) ?>);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="container py-4">
    <h1 class="mb-4">KPI Dashboard</h1>

    <!-- Date filter -->
    <form method="get" class="row g-3 mb-4">
      <div class="col-auto">
        <label for="start" class="form-label">Start Date</label>
        <input type="date" id="start" name="start" class="form-control" value="<?= htmlspecialchars($start) ?>">
      </div>
      <div class="col-auto">
        <label for="end" class="form-label">End Date</label>
        <input type="date" id="end" name="end" class="form-control" value="<?= htmlspecialchars($end) ?>">
      </div>
      <div class="col-auto align-self-end">
        <button class="btn btn-primary">Filter</button>
      </div>
    </form>

    <!-- KPI cards -->
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-white bg-success h-100">
          <div class="card-body">
            <h5 class="card-title">Total Sales</h5>
            <p class="card-text fs-4">$<?= number_format($totalSales, 2) ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-info h-100">
          <div class="card-body">
            <h5 class="card-title">Total Orders</h5>
            <p class="card-text fs-4"><?= $totalOrders ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-warning h-100">
          <div class="card-body">
            <h5 class="card-title">Top Product</h5>
            <p class="card-text fs-4"><?= htmlspecialchars($topProduct['name']) ?> (<?= $topProduct['total_sold'] ?> sold)</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="row mt-5">
      <div class="col-md-6">
        <h4>Top 5 Products Sold</h4>
        <canvas id="topProductsChart"></canvas>
      </div>
      <div class="col-md-6">
        <h4>Sales by Category</h4>
        <canvas id="categorySalesChart"></canvas>
      </div>
    </div>
  </div>

<script>
const topProductLabels = <?= json_encode(array_column($topProducts ?? [], 'name')) ?>;
const topProductData   = <?= json_encode(array_column($topProducts ?? [], 'total_sold')) ?>;

const categoryLabels = <?= json_encode(array_column($categorySales ?? [], 'category_id')) ?>;
const categoryData   = <?= json_encode(array_column($categorySales ?? [], 'total_sold')) ?>;

// Top Products Bar Chart
new Chart(document.getElementById('topProductsChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: topProductLabels,
    datasets: [{
      label: 'Units Sold',
      data: topProductData,
      borderWidth: 1
    }]
  },
  options: {
    plugins: { legend: { display: false } },
    scales: { y: { beginAtZero: true } },
    responsive: true
  }
});

// Category Sales Pie Chart
new Chart(document.getElementById('categorySalesChart').getContext('2d'), {
  type: 'pie',
  data: {
    labels: categoryLabels,
    datasets: [{
      data: categoryData
    }]
  },
  options: {
    responsive: true
  }
});
</script>
</body>
</html>
