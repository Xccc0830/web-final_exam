<?php
// violation_delete.php (PDO 修正版)
require_once("db.php"); // 引入 $pdo

// 1. 取得要刪除的 ID
$id = $_GET['id'] ?? 0;

if ($id > 0) {
    try {
        // 2. 先查詢該筆紀錄是否有檔案路徑，以便刪除硬碟中的照片
        $stmt_file = $pdo->prepare("SELECT evidence_path FROM violations WHERE id = ?");
        $stmt_file->execute([$id]);
        $row = $stmt_file->fetch();

        if ($row && !empty($row['evidence_path'])) {
            // 如果有照片，連同電腦裡的檔案一起刪除，節省空間
            if (file_exists($row['evidence_path'])) {
                unlink($row['evidence_path']);
            }
        }

        // 3. 執行資料庫刪除
        $stmt_del = $pdo->prepare("DELETE FROM violations WHERE id = ?");
        $stmt_del->execute([$id]);

        // 4. 成功後跳轉回列表頁
        header("Location: violation_list_all.php?msg=delete_success");
        exit;

    } catch (PDOException $e) {
        // 如果報錯，顯示原因
        die("刪除失敗: " . $e->getMessage());
    }
} else {
    // 如果沒有 ID，直接回列表
    header("Location: violation_list_all.php");
    exit;
}