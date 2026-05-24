<?php
require_once 'includes/header.php';

// Fetch Visitor Analytics
$total_views = $pdo->query("SELECT count(*) FROM page_views")->fetchColumn();
$unique_visitors = $pdo->query("SELECT count(DISTINCT ip_address) FROM page_views")->fetchColumn();
$msg_count = $pdo->query("SELECT count(*) FROM messages")->fetchColumn();

// Get Views Over Last 7 Days for Bar Chart
$views_7_days = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(*) as count 
    FROM page_views 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
")->fetchAll();

$chart_dates = [];
$chart_views = [];

// Fill missing days with 0
for($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $chart_dates[] = date('M d', strtotime($d));
    $found = false;
    foreach($views_7_days as $v) {
        if($v['date'] == $d) {
            $chart_views[] = $v['count'];
            $found = true;
            break;
        }
    }
    if(!$found) $chart_views[] = 0;
}

// Get Top Pages for Pie Chart
$top_pages = $pdo->query("SELECT page, COUNT(*) as count FROM page_views GROUP BY page ORDER BY count DESC LIMIT 5")->fetchAll();
$pie_labels = [];
$pie_data = [];
foreach($top_pages as $tp) {
    $pie_labels[] = str_replace('.php', '', $tp['page']);
    $pie_data[] = $tp['count'];
}

?>
<div class="row mb-2">
    <div class="col-md-4 mb-4">
        <div class="card bg-white border-0 p-4 h-100 shadow-sm" style="border-bottom: 4px solid var(--accent) !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size:0.75rem; letter-spacing: 1px;">Total Page Views</p>
                    <h2 class="mb-0 fw-bold text-dark"><?= $total_views ?></h2>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:55px; height:55px; background: rgba(255,107,87,0.1); color: var(--accent);">
                    <i class="fa-solid fa-eye fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card bg-white border-0 p-4 h-100 shadow-sm" style="border-bottom: 4px solid #0dcaf0 !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size:0.75rem; letter-spacing: 1px;">Unique Visitors</p>
                    <h2 class="mb-0 fw-bold text-dark"><?= $unique_visitors ?></h2>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:55px; height:55px; background: rgba(13,202,240,0.1); color: #0dcaf0;">
                    <i class="fa-solid fa-users fs-4"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card bg-white border-0 p-4 h-100 shadow-sm" style="border-bottom: 4px solid #198754 !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p class="text-muted mb-1 fw-bold text-uppercase" style="font-size:0.75rem; letter-spacing: 1px;">Messages Received</p>
                    <h2 class="mb-0 fw-bold text-dark"><?= $msg_count ?></h2>
                </div>
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:55px; height:55px; background: rgba(25,135,84,0.1); color: #198754;">
                    <i class="fa-solid fa-envelope-open-text fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart 1: Line Chart (Traffic) -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-chart-line me-2" style="color: var(--accent);"></i>Traffic Over Last 7 Days</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <?php if(empty($chart_views) || array_sum($chart_views) == 0): ?>
                    <div class="alert alert-info border-0 mt-3"><i class="fa-solid fa-circle-info me-2"></i>No traffic data available yet. Visit your public site to generate data!</div>
                <?php else: ?>
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="trafficChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Chart 2: Doughnut Chart (Top Pages) -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-fire me-2 text-warning"></i>Most Visited Pages</h5>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center px-4 pb-4">
                <?php if(empty($pie_data)): ?>
                    <p class="text-muted text-center mt-5">No data yet</p>
                <?php else: ?>
                    <div style="position: relative; height: 320px; width: 100%;">
                        <canvas id="pagesChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    Chart.defaults.font.family = "'Poppins', sans-serif";
    
    <?php if(array_sum($chart_views) > 0): ?>
    // Traffic Line Chart
    const ctxBar = document.getElementById('trafficChart').getContext('2d');
    
    // Create Premium Gradient Fill
    let gradientFill = ctxBar.createLinearGradient(0, 0, 0, 350);
    gradientFill.addColorStop(0, 'rgba(255, 107, 87, 0.5)');
    gradientFill.addColorStop(1, 'rgba(255, 107, 87, 0.0)');

    new Chart(ctxBar, {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_dates) ?>,
            datasets: [{
                label: 'Page Views',
                data: <?= json_encode($chart_views) ?>,
                backgroundColor: gradientFill,
                borderColor: '#ff6b57',
                borderWidth: 4,
                tension: 0.45, // Smoother curves
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#ff6b57',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#ff6b57',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { 
                y: { 
                    beginAtZero: true, 
                    ticks: { precision: 0, padding: 10, color: '#a0a4b8' }, 
                    grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)', drawBorder: false } 
                },
                x: { 
                    ticks: { padding: 10, color: '#a0a4b8' },
                    grid: { display: false, drawBorder: false } 
                }
            },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(30, 33, 43, 0.95)',
                    titleColor: '#a0a4b8',
                    titleFont: { size: 12, family: "'Poppins', sans-serif", weight: 'normal' },
                    bodyFont: { size: 16, family: "'Poppins', sans-serif", weight: 'bold' },
                    padding: 15,
                    cornerRadius: 10,
                    displayColors: false,
                    borderColor: 'rgba(255, 107, 87, 0.3)',
                    borderWidth: 1,
                    caretPadding: 10,
                    callbacks: {
                        label: function(context) { return context.parsed.y + ' Views'; }
                    }
                }
            },
            interaction: { intersect: false, mode: 'index' }
        }
    });
    <?php endif; ?>

    <?php if(!empty($pie_data)): ?>
    // Doughnut Chart
    const ctxPie = document.getElementById('pagesChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($pie_labels) ?>,
            datasets: [{
                data: <?= json_encode($pie_data) ?>,
                backgroundColor: [
                    '#ff6b57', // Coral Accent
                    '#5c5ce0', // Vibrant Purple
                    '#00d2ff', // Electric Cyan
                    '#ffb800', // Sun Yellow
                    '#2cd482'  // Mint Green
                ],
                borderWidth: 0,
                hoverOffset: 12,
                borderRadius: 6, // Premium rounded edges on segments
                spacing: 5 // Spacing between segments
            }]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%', // Sleeker, thinner ring
            plugins: { 
                legend: { 
                    position: 'bottom', 
                    labels: { padding: 25, boxWidth: 12, usePointStyle: true, font: { family: "'Poppins', sans-serif" } } 
                },
                tooltip: {
                    backgroundColor: 'rgba(30, 33, 43, 0.95)',
                    bodyFont: { size: 14, family: "'Poppins', sans-serif", weight: 'bold' },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) { return ' ' + context.parsed + ' Views'; }
                    }
                }
            } 
        }
    });
    <?php endif; ?>
});
</script>
<?php require_once 'includes/footer.php'; ?>