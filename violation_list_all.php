<?php
// violation_list_all.php (PDO 修正版：含檔案路徑與正確欄位)

require_once("db.php"); 
include("header.php");
?>

<div class="container mt-4 mb-5">
    <h2 class="mb-4">違規管理（所有住民）</h2>
    <p>顯示所有住民的違規紀錄及佐證資料。</p>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>住民姓名</th>
                <th>學號</th>
                <th>房號</th>
                <th>違規描述</th>
                <th>違規點數</th>
                <th>建立時間</th>
                <th>佐證資料</th> <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 【重要修正】：SQL 指令必須選取 evidence_path
            $sql = "SELECT v.id AS violation_id, r.name, r.student_id, r.room, 
                           v.violation, v.points, v.created_at, v.evidence_path
                    FROM violations v
                    JOIN residents r ON v.resident_id = r.id
                    ORDER BY v.created_at DESC";

            try {
                $stmt = $pdo->query($sql);
                $violations = $stmt->fetchAll(); 
            } catch (PDOException $e) {
                echo "<tr><td colspan='8' class='text-center text-danger'>查詢錯誤: " . $e->getMessage() . "</td></tr>";
                $violations = [];
            }

            if (count($violations) == 0) {
                echo "<tr><td colspan='8' class='text-center'>目前沒有違規紀錄</td></tr>";
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
                        <?php 
                        $file_ext = strtolower(pathinfo($row['evidence_path'], PATHINFO_EXTENSION));
                        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])): 
                        ?>
                            <a href="<?= htmlspecialchars($row['evidence_path']) ?>" target="_blank">
                                <img src="<?= htmlspecialchars($row['evidence_path']) ?>" alt="佐證圖" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            </a>
                        <?php else: ?>
                            <a href="<?= htmlspecialchars($row['evidence_path']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-file-earmark-pdf">查看附件</i>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="text-muted small">無檔案</span>
                    <?php endif; ?>
                </td>

                <td>
                    <a href="violation_delete.php?id=<?= $row['violation_id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('確定要刪除這筆紀錄？');">刪除</a>
                </td>
            </tr>
            <?php
                endforeach;
            }
            ?>
        </tbody>
    </table>

    <div class="mt-3">
        <a href="violation_create.php" class="btn btn-success shadow-sm">＋ 新增違規紀錄</a>
    </div>
</div>

<?php include("footer.php"); ?>