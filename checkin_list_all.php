<?php
// checkin_list_all.php (PDO 轉換版本 - 已將按鈕移至上方)

// 確保引入 PDO 連線 $pdo
require_once("db.php"); 
include("header.php"); // 頁首
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">簽到管理（所有住民）</h2>
            <p class="text-muted mb-0">顯示所有住民的簽到紀錄。</p>
        </div>
        <div>
            <a href="checkin_create.php" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-lg"></i> ＋ 新增簽到紀錄
            </a>
        </div>
    </div>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
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

            try {
                $stmt = $pdo->query($sql); 
                $checkin_records = $stmt->fetchAll(); 

            } catch (PDOException $e) {
                echo "<tr><td colspan='5' class='text-center text-danger'>資料庫查詢錯誤: " . $e->getMessage() . "</td></tr>";
                $checkin_records = [];
            }

            if (count($checkin_records) == 0) {
                echo "<tr><td colspan='5' class='text-center'>目前沒有簽到紀錄</td></tr>";
            } else {
                foreach($checkin_records as $row):
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
                endforeach;
            }
            ?>
        </tbody>
    </table>
</div>

<?php include("footer.php"); ?>