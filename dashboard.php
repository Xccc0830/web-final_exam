<?php
include("header.php");
include("db.php");

// 1️⃣ 查詢各房號住民數量
$sql_room = "SELECT room, COUNT(*) AS total FROM residents GROUP BY room ORDER BY room";
$room_result = $conn->query($sql_room);

$rooms = [];
$room_totals = [];
while ($row = $room_result->fetch_assoc()) {
    $rooms[] = $row['room'];
    $room_totals[] = $row['total'];
}

// 2️⃣ 查詢違規點數統計
$sql_violation = "SELECT violation, COUNT(*) AS total FROM violations GROUP BY violation";
$vio_result = $conn->query($sql_violation);

$vio_labels = [];
$vio_totals = [];
while ($row = $vio_result->fetch_assoc()) {
    $vio_labels[] = $row['violation'];
    $vio_totals[] = $row['total'];
}

// 3️⃣ 查詢簽到每日次數
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
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container mt-4">
    <h2>宿舍管理儀表板 Dashboard</h2>

    <hr>

    <!-- 1️⃣ 房號入住統計 -->
    <h4>各房號入住人數</h4>
    <canvas id="roomChart" height="150"></canvas>

    <hr>

    <!-- 2️⃣ 違規分布 -->
    <h4>違規紀錄統計</h4>
    <canvas id="vioChart" height="150"></canvas>

    <hr>

    <!-- 3️⃣ 簽到次數折線圖 -->
    <h4>每日簽到次數</h4>
    <canvas id="checkChart" height="150"></canvas>
</div>

<script>
// 1️⃣ 房號入住人數
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
    options: {
        scales: { y: { beginAtZero: true } }
    }
});

// 2️⃣ 違規種類分布
new Chart(document.getElementById("vioChart"), {
    type: "pie",
    data: {
        labels: <?= json_encode($vio_labels) ?>,
        datasets: [{
            data: <?= json_encode($vio_totals) ?>,
        }]
    }
});

// 3️⃣ 每日簽到數量
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
