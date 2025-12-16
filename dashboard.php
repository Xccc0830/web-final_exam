<?php
// dashboard.php

// 確保 Session 已經啟動，以便檢查登入狀態
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 引入 Header (包含 HTML 開頭標籤和導覽列)
include("header.php"); 
require_once 'db.php'; // 【關鍵修正】確保 $conn 在任何查詢前被定義

// 檢查使用者是否已登入 (住宿生)
$is_logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE && isset($_SESSION['resident_id']);

if ($is_logged_in) {
    
    // =======================================================
    // A. 住宿生個人紀錄視圖 (Resident View)
    // =======================================================
    $current_resident_id = $_SESSION['resident_id'];
    $resident_name = htmlspecialchars($_SESSION['name']);
    
    // 查詢住宿生房號
    $room_query = $conn->prepare("SELECT room FROM residents WHERE id = ?");
    $room_query->bind_param("i", $current_resident_id);
    $room_query->execute();
    $resident_room = $room_query->get_result()->fetch_assoc()['room'];
    $room_query->close();
    
    // 查詢違規紀錄
    $violations_query = $conn->prepare("SELECT violation, points, created_at FROM violations WHERE resident_id = ? ORDER BY created_at DESC");
    $violations_query->bind_param("i", $current_resident_id);
    $violations_query->execute();
    $violations_result = $violations_query->get_result();

    // 查詢點名/簽到紀錄
    $checkins_query = $conn->prepare("SELECT checkin_time FROM checkins WHERE resident_id = ? ORDER BY checkin_time DESC");
    $checkins_query->bind_param("i", $current_resident_id);
    $checkins_query->execute();
    $checkins_result = $checkins_query->get_result();

    // 總違規點數
    $points_query = $conn->prepare("SELECT SUM(points) AS total_points FROM violations WHERE resident_id = ?");
    $points_query->bind_param("i", $current_resident_id);
    $points_query->execute();
    $total_points = $points_query->get_result()->fetch_assoc()['total_points'] ?? 0;
    $points_query->close();

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
                    <h4><?= $violations_result->num_rows ?> 筆</h4>
                    <p>總違規次數</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 bg-success text-white">
                    <h4><?= $checkins_result->num_rows ?> 筆</h4>
                    <p>總簽到次數</p>
                </div>
            </div>
        </div>

        <h4>⚠️ 您的違規紀錄</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>違規事項</th><th>點數/扣分</th><th>記錄時間</th></tr></thead>
                <tbody>
                <?php if ($violations_result->num_rows > 0): ?>
                    <?php while($row = $violations_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['violation']); ?></td>
                        <td><?= htmlspecialchars($row['points']); ?></td>
                        <td><?= htmlspecialchars($row['created_at']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center">恭喜！目前沒有您的違規紀錄。</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $violations_query->close(); ?>

        <hr>

        <h4>⏱️ 您的簽到紀錄</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead><tr><th>簽到時間</th></tr></thead>
                <tbody>
                <?php if ($checkins_result->num_rows > 0): ?>
                    <?php while($row = $checkins_result->fetch_assoc()): ?>
                    <tr><td><?= htmlspecialchars($row['checkin_time']); ?></td></tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td class="text-center">目前沒有您的點名紀錄。</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php $checkins_query->close(); ?>

    </div>
    <?php

} else {

    // =======================================================
    // B. 管理員儀表板視圖 (Admin View) - 您的原始程式碼
    // =======================================================

    // 重新執行所有管理員統計查詢
    $total_residents = $conn->query("SELECT COUNT(*) AS total FROM residents")->fetch_assoc()['total'];
    $beds_per_room = 4;
    $total_rooms = $conn->query("SELECT COUNT(DISTINCT room) AS total FROM residents")->fetch_assoc()['total'];
    $total_beds = $total_rooms * $beds_per_room;

    $today = date("Y-m-d");
    $sql_today = "SELECT COUNT(*) AS total FROM checkins WHERE DATE(checkin_time) = '$today'";
    $today_checkins = $conn->query($sql_today)->fetch_assoc()['total'];
    $today_not_check = $total_residents - $today_checkins;

    $sql_room = "SELECT room, COUNT(*) AS total FROM residents GROUP BY room ORDER BY room";
    $room_result = $conn->query($sql_room);
    $rooms = [];
    $room_totals = [];
    while ($row = $room_result->fetch_assoc()) {
        $rooms[] = $row['room'];
        $room_totals[] = $row['total'];
    }

    $sql_violation = "SELECT violation, COUNT(*) AS total FROM violations GROUP BY violation";
    $vio_result = $conn->query($sql_violation);
    $vio_labels = [];
    $vio_totals = [];
    while ($row = $vio_result->fetch_assoc()) {
        $vio_labels[] = $row['violation'];
        $vio_totals[] = $row['total'];
    }

    $sql_checkin = "SELECT DATE(checkin_time) AS day, COUNT(*) AS total 
                    FROM checkins 
                    GROUP BY DATE(checkin_time) 
                    ORDER BY day";
    $check_result = $conn->query($sql_checkin);
    $days = [];
    $check_totals = [];
    while ($row = $check_result->fetch_assoc()) {
        $days[] = $row['day'];
        $check_totals[] = $row['total'];
    }

    $today_result = $conn->query("
        SELECT c.checkin_time, r.name, r.room
        FROM checkins c
        JOIN residents r ON c.resident_id = r.id
        WHERE DATE(c.checkin_time) = '$today'
        ORDER BY c.checkin_time ASC
    ");

    $danger_list = $conn->query("
        SELECT r.name, r.room, SUM(v.points) AS total_points
        FROM violations v
        JOIN residents r ON v.resident_id = r.id
        GROUP BY v.resident_id
        HAVING total_points >= 10
    ");
    ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container mt-4">
        <h2>宿舍管理儀表板 Dashboard</h2>
        <hr>


        <div class="row text-center mb-4">

            <div class="col-md-3">
                <div class="card p-3 bg-primary text-white">
                    <h4><?= $total_residents ?></h4>
                    <p>目前入住</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 bg-success text-white">
                    <h4><?= $total_beds - $total_residents ?></h4>
                    <p>空床位</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 bg-info text-white">
                    <h4><?= $today_checkins ?></h4>
                    <p>今日簽到</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 bg-danger text-white">
                    <h4><?= $today_not_check ?></h4>
                    <p>今日未簽到</p>
                </div>
            </div>

        </div>

        <h4 class="text-danger">⚠ 異常提醒</h4>
        <ul>
            <?php if ($today_checkins == 0): ?>
                <li>⚠ 今天到目前為止無任何人簽到！</li>
            <?php endif; ?>

            <?php if ($danger_list->num_rows == 0): ?>
                <li>未發現違規超過 10 點之住民。</li>
            <?php else: ?>
                <?php while ($d = $danger_list->fetch_assoc()): ?>
                    <li>⚠ <?= $d['name'] ?>（房號 <?= $d['room'] ?>）違規累積 <?= $d['total_points'] ?> 點</li>
                <?php endwhile; ?>
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
                    <?php if ($today_result->num_rows == 0): ?>
                        <tr><td colspan="3" class="text-center">今日尚無簽到紀錄</td></tr>
                    <?php else: ?>
                        <?php while ($t = $today_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $t['name'] ?></td>
                                <td><?= $t['room'] ?></td>
                                <td><?= $t['checkin_time'] ?></td>
                            </tr>
                        <?php endwhile; ?>
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
} // End of else (Admin View)
?>

<?php 
$conn->close();
include("footer.php"); 
?>