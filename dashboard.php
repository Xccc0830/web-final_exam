<?php
include("header.php");
include("db.php");

/* ===========================
   1️⃣ KPI 數據
   =========================== */

// 目前住民數
$total_residents = $conn->query("SELECT COUNT(*) AS total FROM residents")
                        ->fetch_assoc()['total'];

// 設定每房 4 床（可自行更改）
$beds_per_room = 4;

// 查詢總房間數
$total_rooms = $conn->query("SELECT COUNT(DISTINCT room) AS total FROM residents")
                    ->fetch_assoc()['total'];

// 計算總床位
$total_beds = $total_rooms * $beds_per_room;

// 查詢今日簽到
$today = date("Y-m-d");
$sql_today = "SELECT COUNT(*) AS total FROM checkins WHERE DATE(checkin_time) = '$today'";
$today_checkins = $conn->query($sql_today)->fetch_assoc()['total'];

// 今日未簽到
$today_not_check = $total_residents - $today_checkins;

/* ===========================
   2️⃣ 查詢各房號住民數量
   =========================== */
$sql_room = "SELECT room, COUNT(*) AS total FROM residents GROUP BY room ORDER BY room";
$room_result = $conn->query($sql_room);

$rooms = [];
$room_totals = [];
while ($row = $room_result->fetch_assoc()) {
    $rooms[] = $row['room'];
    $room_totals[] = $row['total'];
}

/* ===========================
   3️⃣ 違規種類統計
   =========================== */
$sql_violation = "SELECT violation, COUNT(*) AS total FROM violations GROUP BY violation";
$vio_result = $conn->query($sql_violation);

$vio_labels = [];
$vio_totals = [];
while ($row = $vio_result->fetch_assoc()) {
    $vio_labels[] = $row['violation'];
    $vio_totals[] = $row['total'];
}

/* ===========================
   4️⃣ 每日簽到折線圖
   =========================== */
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

/* ===========================
   5️⃣ 今日簽到列表
   =========================== */
$today_result = $conn->query("
    SELECT c.checkin_time, r.name, r.room
    FROM checkins c
    JOIN residents r ON c.resident_id = r.id
    WHERE DATE(c.checkin_time) = '$today'
    ORDER BY c.checkin_time ASC
");

/* ===========================
   6️⃣ 違規超過 10 點者
   =========================== */
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

    <!-- 1️⃣ KPI 儀表板 -->
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

    <!-- 2️⃣ 異常提醒 -->
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

    <!-- 3️⃣ 今日簽到列表 -->
    <h4>今日簽到名單</h4>
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

    <hr>

    <!-- 4️⃣ 原本的三張圖表保留 -->
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
// 房號入住
new Chart(document.getElementById("roomChart"), {
    type: "bar",
    data: {
        labels: <?= json_encode($rooms) ?>,
        datasets: [{
            label: "住民人數",
            data: <?= json_encode($room_totals) ?>,
            borderWidth: 1
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});

// 違規
new Chart(document.getElementById("vioChart"), {
    type: "pie",
    data: {
        labels: <?= json_encode($vio_labels) ?>,
        datasets: [{ data: <?= json_encode($vio_totals) ?> }]
    }
});

// 每日簽到
new Chart(document.getElementById("checkChart"), {
    type: "line",
    data: {
        labels: <?= json_encode($days) ?>,
        datasets: [{
            label: "簽到次數",
            data: <?= json_encode($check_totals) ?>,
            borderWidth: 2,
            fill: false
        }]
    }
});
</script>

<?php include("footer.php"); ?>
