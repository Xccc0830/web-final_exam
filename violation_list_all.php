<?php
// violation_list_all.php (修正參數綁定錯誤版本)
require_once("db.php"); 
include("header.php");

// 1. 取得搜尋字
$k = $_GET['k'] ?? '';
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">違規管理（所有住民）</h2>

    <form method="GET" class="mb-4 d-flex gap-2">
        <input type="text" name="k" class="form-control w-25" placeholder="搜尋姓名、學號或房號" value="<?= htmlspecialchars($k) ?>">
        <button type="submit" class="btn btn-primary">搜尋</button>
        <a href="violation_list_all.php" class="btn btn-outline-secondary">清除</a>
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
            // 2. 組合 SQL (使用 JOIN 獲取住民資料)
            $sql = "SELECT v.id AS violation_id, r.name, r.student_id, r.room, 
                           v.violation, v.points, v.created_at, v.evidence_path
                    FROM violations v
                    JOIN residents r ON v.resident_id = r.id";

            $params = [];
            if ($k !== '') {
                // 修改重點：雖然佔位符名稱相同，但在某些 PDO 驅動下需確保對應
                // 或是直接在 SQL 使用不同的佔位符
                $sql .= " WHERE r.name LIKE :k1 OR r.student_id LIKE :k2 OR r.room LIKE :k3";
                $searchTerm = "%$k%";
                $params = [
                    'k1' => $searchTerm,
                    'k2' => $searchTerm,
                    'k3' => $searchTerm
                ];
            }
            $sql .= " ORDER BY v.created_at DESC";

            // 3. 執行查詢
            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $violations = $stmt->fetchAll();

                if (count($violations) == 0) {
                    // colspan 修正為 8 (對應 8 個 <th>)
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
                    <a href="violation_delete.php?id=<?= $row['violation_id'] ?>&redirect=all" 
                       class="btn btn-danger btn-sm" onclick="return confirm('確定刪除？');">刪除</a>
                </td>
            </tr>
            <?php 
                    endforeach; 
                }
            } catch (PDOException $e) {
                echo "<tr><td colspan='8' class='alert alert-danger'>查詢出錯：" . $e->getMessage() . "</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="violation_create.php" class="btn btn-success">＋ 新增違規紀錄</a>
        <a href="resident_list.php" class="btn btn-secondary">返回住民列表</a>
    </div>
</div>

<?php include("footer.php"); ?>