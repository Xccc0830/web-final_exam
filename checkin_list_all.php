<?php
// checkin_list_all.php (PDO 轉換版本)

// 確保引入 PDO 連線 $pdo
require_once("db.php"); 
include("header.php"); // 頁首
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

            try {
                // 1. 【PDO 修正】：使用 $pdo->query() 執行查詢 (無使用者輸入，無需預備語句)
                // 這是程式碼第 27 行的位置，將 $conn->query() 換成 $pdo->query()
                $stmt = $pdo->query($sql); 
                
                // 2. 【PDO 修正】：使用 fetchAll() 獲取所有結果到陣列
                $checkin_records = $stmt->fetchAll(); 

            } catch (PDOException $e) {
                echo "<tr><td colspan='5' class='text-center text-danger'>資料庫查詢錯誤: " . $e->getMessage() . "</td></tr>";
                $checkin_records = []; // 設置為空陣列
            }

            if (count($checkin_records) == 0) {
                // 3. 【PDO 修正】：使用 count() 檢查結果數量
                echo "<tr><td colspan='5' class='text-center'>目前沒有簽到紀錄</td></tr>";
            } else {
                // 4. 【PDO 修正】：使用 foreach 迴圈遍歷陣列
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

    <a href="checkin_create.php" class="btn btn-success">新增簽到紀錄</a>
</div>

<?php
include("footer.php"); // 頁尾
?>