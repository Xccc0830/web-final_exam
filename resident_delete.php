<?php
// resident_delete.php
require_once 'db.php'; 

// 取得 URL 傳遞的 ID
$id = $_GET['id'] ?? 0;

if ($id) {
    try {
        // 使用 $pdo 進行預處理，防止 SQL 注入
        $stmt = $pdo->prepare("DELETE FROM residents WHERE id = ?");
        if ($stmt->execute([$id])) {
            // 刪除成功後跳轉回列表
            header("Location: resident_list.php?msg=delete_success"); 
            exit;
        }
    } catch (PDOException $e) {
        die("刪除失敗: " . $e->getMessage());
    }
} else {
    header("Location: resident_list.php?error=no_id");
    exit;
}
?>