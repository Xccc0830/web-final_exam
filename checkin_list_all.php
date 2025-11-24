<?php
include("db.php");       // 連線資料庫
include("header.php");   // 頁首
?>

<div class="container mt-4">
    <h2>簽到管理（所有住民）</h2>
    <p>顯示所有住民的簽到紀錄。</p>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>住民姓名</th>
                <th>學號</th>
                <th>房號</th>
                <th>簽到時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT c.id AS checkin_id, r.name, r.student_id, r.room, c.checkin_time
                    FROM checkins c
                    JOIN residents r ON c.resident_id = r.id
                    ORDER BY c.checkin_time DESC";

            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                echo "<tr><td colspan='5' class='text-center'>目前沒有簽到紀錄</td></tr>";
            } else {
                while($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['room']) ?></td>
                <td><?= htmlspecialchars($row['checkin_time']) ?></td>
                <td>
                    <a href="checkin_delete.php?id=<?= $row['checkin_id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('確定要刪除這筆簽到紀錄嗎？');">刪除</a>
                </td>
            </tr>
            <?php
                endwhile;
            }
            ?>
        </tbody>
    </table>

    <a href="checkin_create.php" class="btn btn-success">新增簽到紀錄</a>
</div>

<?php
include("footer.php"); // 頁尾
?>
