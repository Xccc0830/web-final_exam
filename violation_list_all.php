<?php
// violation_list_all.php (PDO 轉換版本)

// 確保引入 PDO 連線 $pdo
require_once("db.php"); 
include("header.php"); // 頁首
?>

<h2>違規管理（所有住民）</h2>
<p>顯示所有住民的違規紀錄及總點數。</p>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>住民姓名</th>
            <th>學號</th>
            <th>房號</th>
            <th>違規描述</th>
            <th>違規點數</th>
            <th>建立時間</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // 修正 SQL 語句
        $sql = "SELECT v.id AS violation_id, r.id AS resident_id, r.name, r.student_id, r.room, 
                         v.violation, v.points, v.created_at
                 FROM violations v
                 JOIN residents r ON v.resident_id = r.id
                 ORDER BY r.name, v.id";

        try {
            // 1. 【PDO 修正】：使用 $pdo->query() 執行查詢
            $stmt = $pdo->query($sql);

            // 2. 【PDO 修正】：使用 fetchAll() 獲取所有結果
            $violations = $stmt->fetchAll(); 

        } catch (PDOException $e) {
            // 處理資料庫查詢錯誤
            echo "<tr><td colspan='7' class='text-center text-danger'>資料庫查詢錯誤: " . $e->getMessage() . "</td></tr>";
            $violations = []; // 設置為空陣列以避免後續錯誤
        }

        if (count($violations) == 0) {
            echo "<tr><td colspan='7' class='text-center'>目前沒有違規紀錄</td></tr>";
        } else {
            // 3. 【PDO 修正】：使用 foreach 迴圈遍歷陣列
            foreach($violations as $row):
        ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['student_id']) ?></td>
            <td><?= htmlspecialchars($row['room']) ?></td>
            <td><?= htmlspecialchars($row['violation']) ?></td>
            <td><?= htmlspecialchars($row['points']) ?></td>
            <td><?= htmlspecialchars($row['created_at']) ?></td>
            <td>
                <a href="violation_delete.php?id=<?= $row['violation_id'] ?>" class="btn btn-danger btn-sm"
                   onclick="return confirm('確定要刪除這筆違規紀錄嗎？');">刪除</a>
            </td>
        </tr>
        <?php
            endforeach;
        }
        ?>
    </tbody>
</table>

<a href="violation_create.php" class="btn btn-success">＋ 新增違規紀錄</a>

<?php 
// 刪除 $result->close() 和 $conn->close() 等 mysqli 函數
include("footer.php"); 
?>