<?php
// dashboard.php (PDO 轉換版本)

// 確保 Session 已經啟動，以便檢查登入狀態
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 引入 Header (包含 HTML 開頭標籤和導覽列)
include("header.php"); 
require_once 'db.php'; // 現在引入的是 $pdo

// 檢查使用者是否已登入
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE && isset($_SESSION['resident_id']);
// 檢查是否為管理員
$is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// 判斷是否為「學生」登入，顯示個人視圖
if ($is_logged_in && !$is_admin) {
    
    // =======================================================
    // A. 住宿生個人紀錄視圖 (Student Resident View) - 使用 PDO 轉換
    // =======================================================
    $current_resident_id = $_SESSION['resident_id'];
    $resident_name = htmlspecialchars($_SESSION['name']);
    
    // 查詢住宿生房號 (PDO 安全)
    $room_stmt = $pdo->prepare("SELECT room FROM residents WHERE id = ?");
    $room_stmt->execute([$current_resident_id]);
    $resident_room = $room_stmt->fetchColumn(); // 使用 fetchColumn 直接取單欄資料

    // 查詢違規紀錄 (PDO 安全)
    $violations_stmt = $pdo->prepare("SELECT violation, points, created_at FROM violations WHERE resident_id = ? ORDER BY created_at DESC");
    $violations_stmt->execute([$current_resident_id]);
    $violations_result = $violations_stmt->fetchAll();

    // 查詢點名/簽到紀錄 (PDO 安全)
    $checkins_stmt = $pdo->prepare("SELECT checkin_time FROM checkins WHERE resident_id = ? ORDER BY checkin_time DESC");
    $checkins_stmt->execute([$current_resident_id]);
    $checkins_result = $checkins_stmt->fetchAll();

    // 總違規點數 (PDO 安全)
    $points_stmt = $pdo->prepare("SELECT SUM(points) AS total_points FROM violations WHERE resident_id = ?");
    $points_stmt->execute([$current_resident_id]);
    $total_points = $points_stmt->fetch()['total_points'] ?? 0;

    ?>
    <div class="container mt-4">
        <h2>👋 歡迎，<?php echo $resident_name; ?> 同學！</h2>
        <h4 class="text-secondary">(房號: <?php echo htmlspecialchars($resident_room); ?>)</h4>
        <hr>
        
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card p-3 bg-warning text-white">
                    <h4><?= $total_points ?> 點</h4>
                    <p>累積違規點數</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-info text-white">
                    <h4><?= count($violations_result) ?> 筆</h4>
                    <p>總違規次數</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-success text-white">
                    <h4><?= count($checkins_result) ?> 筆</h4>
                    <p>總簽到次數</p>
                </div>
            </div>
        </div>

        <h4>⚠️ 您的違規紀錄</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>違規事項</th><th>點數/扣分</th><th>記錄時間</th></tr></thead>
                <tbody>
                <?php if (count($violations_result) > 0): ?>
                    <?php foreach($violations_result as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['violation']); ?></td>
                        <td><?= htmlspecialchars($row['points']); ?></td>
                        <td><?= htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">恭喜！目前沒有您的違規紀錄。</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <hr>

        <h4>⏱️ 您的簽到紀錄</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>簽到時間</th></tr></thead>
                <tbody>
                <?php if (count($checkins_result) > 0): ?>
                    <?php foreach($checkins_result as $row): ?>
                    <tr><td><?= htmlspecialchars($row['checkin_time']); ?></td></tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td class="text-center">目前沒有您的點名紀錄。</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php

// 判斷是否為「Admin」登入，顯示管理員儀表板
} elseif ($is_admin) {

    // =======================================================
    // B. 管理員儀表板視圖 (Admin View) - 使用 PDO 轉換
    // =======================================================

    // 統計查詢 (PDO 轉換：使用 $pdo->query()->fetchColumn() 效率最高)
    $total_residents = $pdo->query("SELECT COUNT(*) FROM residents")->fetchColumn();
    $total_rooms = $pdo->query("SELECT COUNT(DISTINCT room) FROM residents")->fetchColumn();

    $today = date("Y-m-d");

    // 查詢今日簽到人數 (PDO 安全)
    $sql_today_stmt = $pdo->prepare("SELECT COUNT(*) FROM checkins WHERE DATE(checkin_time) = ?");
    $sql_today_stmt->execute([$today]);
    $today_checkins = $sql_today_stmt->fetchColumn();
    $today_not_check = $total_residents - $today_checkins;
    
    // 查詢各房號入住人數 (PDO 轉換)
    $sql_room = "SELECT room, COUNT(*) AS total FROM residents GROUP BY room ORDER BY room";
    $room_result = $pdo->query($sql_room)->fetchAll();
    $rooms = [];
    $room_totals = [];
    foreach ($room_result as $row) {
        $rooms[] = $row['room'];
        $room_totals[] = $row['total'];
    }

    // 查詢違規紀錄統計 (PDO 轉換)
    $sql_violation = "SELECT violation, COUNT(*) AS total FROM violations GROUP BY violation";
    $vio_result = $pdo->query($sql_violation)->fetchAll();
    $vio_labels = [];
    $vio_totals = [];
    foreach ($vio_result as $row) {
        $vio_labels[] = $row['violation'];
        $vio_totals[] = $row['total'];
    }

    // 查詢每日簽到次數 (PDO 轉換)
    $sql_checkin = "SELECT DATE(checkin_time) AS day, COUNT(*) AS total 
                    FROM checkins 
                    GROUP BY DATE(checkin_time) 
                    ORDER BY day";
    $check_result = $pdo->query($sql_checkin)->fetchAll();
    $days = [];
    $check_totals = [];
    foreach ($check_result as $row) {
        $days[] = $row['day'];
        $check_totals[] = $row['total'];
    }

    // 查詢今日簽到名單 (PDO 安全)
    $today_query = $pdo->prepare("
        SELECT c.checkin_time, r.name, r.room
        FROM checkins c
        JOIN residents r ON c.resident_id = r.id
        WHERE DATE(c.checkin_time) = ?
        ORDER BY c.checkin_time ASC
    ");
    $today_query->execute([$today]);
    $today_result = $today_query->fetchAll();

    // 查詢危險名單 (PDO 轉換)
    $danger_list = $pdo->query("
        SELECT r.name, r.room, SUM(v.points) AS total_points
        FROM violations v
        JOIN residents r ON v.resident_id = r.id
        GROUP BY v.resident_id
        HAVING total_points >= 10
    ")->fetchAll();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    

    <div class="container mt-4">
        <h2>📊 宿舍管理儀表板 Dashboard</h2>
        <hr>


        <div class="row text-center mb-4">
            <div class="col-md-4">
                <div class="card p-3 bg-primary text-white">
                    <h4><?= $total_residents ?></h4>
                    <p>目前入住總人數</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 bg-info text-white">
                    <h4><?= $today_checkins ?></h4>
                    <p>今日已簽到人數</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3 bg-danger text-white">
                    <h4><?= $today_not_check ?></h4>
                    <p>今日未簽到人數</p>
                </div>
            </div>

        </div>

        <h4 class="text-danger">⚠ 異常提醒</h4>
        <ul>
            <?php if ($today_checkins == 0 && $total_residents > 0): ?>
                <li>⚠ 今天到目前為止無任何人簽到！</li>
            <?php endif; ?>

            <?php if (count($danger_list) == 0): ?>
                <li>未發現違規累積超過 10 點之住民。</li>
            <?php else: ?>
                <?php foreach ($danger_list as $d): ?>
                    <li>⚠ <?= htmlspecialchars($d['name']) ?>（房號 <?= htmlspecialchars($d['room']) ?>）違規累積 **<?= htmlspecialchars($d['total_points']) ?> 點**</li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <hr>

        <h4>今日簽到名單</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>姓名</th>
                        <th>房號</th>
                        <th>簽到時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($today_result) == 0): ?>
                        <tr><td colspan="3" class="text-center">今日尚無簽到紀錄</td></tr>
                    <?php else: ?>
                        <?php foreach ($today_result as $t): ?>
                            <tr>
                                <td><?= htmlspecialchars($t['name']) ?></td>
                                <td><?= htmlspecialchars($t['room']) ?></td>
                                <td><?= htmlspecialchars($t['checkin_time']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <hr>
        <h4>各房號入住人數</h4>
        <canvas id="roomChart" height="150"></canvas>
        <hr>

        <h4>違規紀錄統計</h4>
        <canvas id="vioChart" height="150"></canvas>
        <hr>

        <h4>每日簽到次數</h4>
        <canvas id="checkChart" height="150"></canvas>
    </div>

    <script>
    // Chart.js 程式碼 (資料來源已由 PDO 轉換，此處保持不變)
    new Chart(document.getElementById("roomChart"), {
        type: "bar",
        data: {
            labels: <?= json_encode($rooms) ?>,
            datasets: [{
                label: "住民人數",
                data: <?= json_encode($room_totals) ?>,
                borderWidth: 1,
                backgroundColor: 'rgba(54, 162, 235, 0.5)'
            }]
        },
        options: { scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById("vioChart"), {
        type: "pie",
        data: {
            labels: <?= json_encode($vio_labels) ?>,
            datasets: [{ 
                data: <?= json_encode($vio_totals) ?>,
                backgroundColor: ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56']
            }]
        }
    });

    new Chart(document.getElementById("checkChart"), {
        type: "line",
        data: {
            labels: <?= json_encode($days) ?>,
            datasets: [{
                label: "簽到次數",
                data: <?= json_encode($check_totals) ?>,
                borderWidth: 2,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)'
            }]
        }
    });
    </script>
    <?php 

} else {
    // C. 未登入：導向登入頁面
    header("location: login.php");
    exit;
}
?>

<?php 
// 由於我們在 db.php 中拋出例外，不再需要手動關閉 $conn
include("footer.php"); 
?>