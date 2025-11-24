<?php
include("db.php");   // 連線資料庫
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
        // 修正 SQL，對應你的欄位
        $sql = "SELECT v.id AS violation_id, r.id AS resident_id, r.name, r.student_id, r.room, 
                       v.violation, v.points, v.created_at
                FROM violations v
                JOIN residents r ON v.resident_id = r.id
                ORDER BY r.name, v.id";

        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            echo "<tr><td colspan='7' class='text-center'>目前沒有違規紀錄</td></tr>";
        } else {
            while($row = $result->fetch_assoc()):
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
            endwhile;
        }
        ?>
    </tbody>
</table>

<a href="violation_create.php" class="btn btn-success">＋ 新增違規紀錄</a>

<?php include("footer.php"); ?>
