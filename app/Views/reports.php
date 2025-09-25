<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<style>
    body {
        font-family: monospace;
    }

    /* Sidebar style */
    .sidebar {
        width: 150px;
        min-height: 100vh;
        transition: all 0.3s ease-in-out;
        background-color: #fff;
        border-right: 1px solid #ddd;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .main-content {
        margin-left: 150px;
        transition: all 0.3s ease-in-out;
        position: relative;
    }

    .toggle-btn {
        position: fixed;
        top: 20px;
        z-index: 2001;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: left 0.25s ease-in-out, transform 0.25s ease-in-out;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        background-color: #fff;
        left: 170px;
    }

    .sidebar.collapsed {
        margin-left: -250px;
    }

    .main-content.expanded {
        margin-left: 0;
    }

    .sidebar.collapsed+.main-content .toggle-btn {
        left: 12px;
    }

    .sidebar.collapsed+.main-content .toggle-btn i {
        transform: rotate(180deg);
    }

    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            height: 100vh;
        }

        .main-content {
            margin-left: 0;
        }
    }

    .nav-link.d-flex.align-items-center {
        font-size: 0.8rem;
        font-weight: 500;
        color: #333;
    }

    .nav-link.d-flex.align-items-center:hover {
        background-color: #f8f9fa;
        color: #000;
        text-decoration: none;
    }

    .logout-pointer {
        cursor: pointer;
    }
</style>

<body class="bg-light">
    <div class="d-flex">

        <div class="d-flex">
            <!-- Sidebar -->
            <nav id="sidebar" class="sidebar bg-white border-end">
                <div class="sidebar-header d-flex align-items-center px-3 py-3 border-bottom">
                    <h5 class="mb-0"><i class="bi bi-box"></i> Product Management</h5>
                </div>
                <ul class="nav flex-column px-2 mt-3">
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center" href="<?= base_url('/dashboard') ?>">
                            <i class="bi bi-speedometer2 me-2"></i> Product Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center" href="<?= base_url('/product_card') ?>">
                            <i class="bi bi-upc-scan me-2"></i> Products with Barcode
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center" href="<?= base_url('/reports') ?>">
                            <i class="bi bi-bar-chart-line me-2"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a href="<?= base_url('/logout') ?>" class="nav-link text-danger d-flex align-items-center logout-pointer logout-link">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>

                </ul>
            </nav>

            <!-- Main Content -->
            <div class="main-content flex-grow-1 p-4">
                <button id="toggleSidebar" class="btn btn-outline-secondary toggle-btn"><i class="bi bi-file"></i></button>

                <div class="container-fluid">
                    <h2 class="mb-3 text-center">Reports & Analytics</h2>

                    <?php
                    $totalProducts = isset($totalCount) ? (int)$totalCount : (isset($products) ? count($products) : 0);
                    $today = date('Y-m-d');
                    $createdToday = 0;
                    $avgPrice = 0.0;

                    $productsList = isset($products) ? $products : [];

                    $priceSum = 0.0;
                    $priceCount = 0;
                    if (!empty($productsList)) {
                        foreach ($productsList as $p) {
                            if (isset($p['price']) && $p['price'] !== '') {
                                $val = floatval($p['price']);
                                if (is_numeric($val)) {
                                    $priceSum += $val;
                                    $priceCount++;
                                }
                            }
                            if (!empty($p['created_at'])) {
                                if (date('Y-m-d', strtotime($p['created_at'])) === $today) {
                                    $createdToday++;
                                }
                            }
                        }
                    }

                    if ($priceCount > 0) {
                        $avgPrice = $priceSum / $priceCount;
                    }

                    $monthlyLabels = [];
                    $monthlyData = [];
                    for ($i = 5; $i >= 0; $i--) {
                        $m = date('Y-m', strtotime("-{$i} month"));
                        $label = date('M Y', strtotime($m . '-01'));
                        $monthlyLabels[] = $label;
                        $monthlyData[$m] = 0;
                    }
                    if (!empty($productsList)) {
                        foreach ($productsList as $pr) {
                            if (!empty($pr['created_at'])) {
                                $key = date('Y-m', strtotime($pr['created_at']));
                                if (array_key_exists($key, $monthlyData)) $monthlyData[$key]++;
                            }
                        }
                    }
                    $monthlyCounts = array_values($monthlyData);

                    $topProducts = $productsList;
                    usort($topProducts, function ($a, $b) {
                        return floatval($b['price'] ?? 0) <=> floatval($a['price'] ?? 0);
                    });
                    $topProducts = array_slice($topProducts, 0, 10);
                    ?>

                    <!-- Summary cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">Total Products</h6>
                                    <h3 class="mb-0"><?php echo $totalProducts ?></h3>
                                    <small class="text-muted">All time</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">Created Today</h6>
                                    <h3 class="mb-0"><?php echo $createdToday ?></h3>
                                    <small class="text-muted"><?php echo $today ?></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-1">Average Price</h6>
                                    <h3 class="mb-0">₱<?php echo number_format($avgPrice, 2) ?></h3>
                                    <small class="text-muted">Across listed products</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-12">
                            <div class="card shadow-sm h-100 chart-card">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Products Added (Last 6 months)</h6>
                                    <div class="flex-grow-1">
                                        <canvas id="lineMonthly" style="width:100%;height:100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="card shadow-sm h-100 chart-card">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Top Priced Products</h6>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div></div>
                                        <div>
                                            <button id="downloadTopCsv" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Download CSV</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="text-end">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($topProducts)): ?>
                                                    <?php foreach ($topProducts as $tp): ?>
                                                        <tr>
                                                            <td><?= esc($tp['product_name'] ?? $tp['name'] ?? '—') ?></td>
                                                            <td class="text-end">₱<?= number_format(floatval($tp['price'] ?? 0), 2) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No products available</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- container-fluid -->
            </div> <!-- main-content -->
        </div> <!-- d-flex -->

        <script>
            const monthlyLabels = <?= json_encode($monthlyLabels) ?>;
            const monthlyCounts = <?= json_encode($monthlyCounts) ?>;

            function generateColors(n) {
                const palette = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#6f42c1', '#fd7e14', '#20c997', '#0dcaf0'];
                const out = [];
                for (let i = 0; i < n; i++) out.push(palette[i % palette.length]);
                return out;
            }

            const ctxLine = document.getElementById('lineMonthly').getContext('2d');
            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Products added',
                        data: monthlyCounts,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,0.12)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: { maxRotation: 0, autoSkip: true }
                        },
                        y: { beginAtZero: true, precision: 0 }
                    }
                }
            });
        </script>

        <script>
            const topProducts = <?= json_encode($topProducts) ?> || [];

            function convertToCSV(items) {
                if (!items || !items.length) return '';
                const header = ['name', 'price'];
                const lines = [header.join(',')];
                items.forEach(it => {
                    const name = (it.product_name ?? it.name ?? '').toString().replace(/\"/g, '""');
                    const price = parseFloat(it.price ?? 0).toFixed(2);
                    const esc = (v) => (/[",\n]/.test(v) ? '"' + v.replace(/"/g, '""') + '"' : v);
                    lines.push([esc(name), esc(price)].join(','));
                });
                return lines.join('\r\n');
            }

            function downloadCSV(filename, csvContent) {
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                if (navigator.msSaveBlob) {
                    navigator.msSaveBlob(blob, filename);
                } else {
                    const link = document.createElement('a');
                    if (link.download !== undefined) {
                        const url = URL.createObjectURL(blob);
                        link.setAttribute('href', url);
                        link.setAttribute('download', filename);
                        link.style.visibility = 'hidden';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        URL.revokeObjectURL(url);
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('downloadTopCsv');
                if (!btn) return;
                if (!topProducts || topProducts.length === 0) {
                    btn.disabled = true;
                    btn.title = 'No top products to export';
                    return;
                }
                btn.addEventListener('click', function() {
                    const csv = convertToCSV(topProducts);
                    const now = new Date();
                    const timestamp = now.toISOString().slice(0, 19).replace(/:/g, '-');
                    downloadCSV('top-prices-' + timestamp + '.csv', csv);
                });
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const sidebar = document.getElementById("sidebar");
                const mainContent = document.querySelector(".main-content");
                const toggleBtn = document.getElementById("toggleSidebar");
                toggleBtn.addEventListener("click", () => {
                    const isCollapsed = sidebar.classList.toggle("collapsed");
                    mainContent.classList.toggle("expanded");
                    const icon = toggleBtn.querySelector('i');
                    if (icon) icon.className = isCollapsed ? 'bi bi-chevron-right' : 'bi bi-file';
                    try { localStorage.setItem('si_crud_sidebar_collapsed', String(isCollapsed)); } catch (e) {}
                });
                try {
                    const collapsed = localStorage.getItem('si_crud_sidebar_collapsed') === 'true';
                    if (collapsed) {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('expanded');
                        const icon = toggleBtn.querySelector('i');
                        if (icon) icon.className = 'bi bi-chevron-right';
                    }
                } catch (e) {}
            });

            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.logout-link').forEach(function(el) {
                    el.addEventListener('click', function(e) {
                        e.preventDefault();
                        const href = this.getAttribute('href');
                        Swal.fire({
                            title: 'Are you sure you want to logout?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, logout',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Successfully logged out',
                                    showConfirmButton: false,
                                    timer: 1400,
                                    timerProgressBar: true
                                }).then(() => {
                                    try {
                                        const url = new URL(href, window.location.origin);
                                        url.searchParams.set('silent', '1');
                                        window.location.href = url.toString();
                                    } catch (err) {
                                        window.location.href = href + (href.includes('?') ? '&' : '?') + 'silent=1';
                                    }
                                });
                            }
                        });
                    });
                });
            });
        </script>

</body>
</html>
