<?php
// violation_list_all.php (超簡易搜尋版)
require_once("db.php"); 
include("header.php");

// 1. 取得搜尋字 (k 代表 keyword)
$k = $_GET['k'] ?? '';
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">違規管理（所有住民）</h2>

    <form method="GET" class="mb-4">
        <input type="text" name="k" placeholder="搜尋姓名或房號" value="<?= htmlspecialchars($k) ?>">
        <button type="submit">搜尋</button>
        <a href="violation_list_all.php">清除</a>
    </form>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>住民姓名</th>
                <th>學號</th>
                <th>房號</th>
                <th>違規描述</th>
                <th>違規點數</th>
                <th>建立時間</th>
                <th width="100">佐證資料</th> 
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 2. 組合 SQL
            $sql = "SELECT v.id AS violation_id, r.name, r.student_id, r.room, 
                           v.violation, v.points, v.created_at, v.evidence_path
                    FROM violations v
                    JOIN residents r ON v.resident_id = r.id";

            if ($k !== '') {
                $sql .= " WHERE r.name LIKE :k OR r.student_id LIKE :k OR r.room LIKE :k";
            }
            $sql .= " ORDER BY v.created_at DESC";

            // 3. 執行查詢
            $stmt = $pdo->prepare($sql);
            if ($k !== '') {
                $stmt->execute(['k' => "%$k%"]);
            } else {
                $stmt->execute();
            }
            $violations = $stmt->fetchAll();

            if (count($violations) == 0) {
                echo "<tr><td colspan='8' class='text-center'>找不到資料</td></tr>";
            } else {
                foreach($violations as $row):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['student_id']) ?></td>
                <td><?= htmlspecialchars($row['room']) ?></td>
                <td><?= htmlspecialchars($row['violation']) ?></td>
                <td><span class="badge bg-danger">扣 <?= htmlspecialchars($row['points']) ?> 點</span></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td class="text-center">
                    <?php if (!empty($row['evidence_path'])): ?>
                        <a href="<?= htmlspecialchars($row['evidence_path']) ?>" target="_blank">
                            <img src="<?= htmlspecialchars($row['evidence_path']) ?>" 
                                 style="width: 50px; height: 50px; object-fit: cover;"
                                 onerror="this.src='https://placehold.co/50?text=Error';">
                        </a>
                    <?php else: ?>
                        <span class="text-muted small">無檔案</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="violation_delete.php?id=<?= $row['violation_id'] ?>" 
                       class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？');">刪除</a>
                </td>
            </tr>
            <?php endforeach; } ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="violation_create.php" class="btn btn-success">＋ 新增違規紀錄</a>
    </div>
</div>

<?php include("footer.php"); ?>