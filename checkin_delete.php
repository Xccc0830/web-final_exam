<?php
// checkin_delete.php (PDO 轉換版本)

require_once("db.php");
// 這裡可以加上 Admin 權限檢查

$id = $_GET['id'] ?? 0;
$resident_id = $_GET['resident_id'] ?? 0;

if (!$id || !is_numeric($id)) {
    // 如果沒有 ID，導回列表
    header("Location: checkin_list_all.php?error=invalid_id");
    exit;
}

try {
    // 準備 DELETE 語句 (PDO 預備語句)
    $stmt = $pdo->prepare("DELETE FROM checkins WHERE id=?");
    
    // 執行並綁定參數
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        // 刪除成功，根據是否有 resident_id 導向不同頁面
        $redirect_url = $resident_id ? "checkin_list.php?resident_id=$resident_id&msg=delete_success" : "checkin_list_all.php?msg=delete_success";
        header("Location: $redirect_url");
        exit;
    } else {
        // 刪除失敗 (可能是 ID 不存在)
        $redirect_url = $resident_id ? "checkin_list.php?resident_id=$resident_id&error=not_found" : "checkin_list_all.php?error=not_found";
        header("Location: $redirect_url");
        exit;
    }
} catch (PDOException $e) {
    // 資料庫錯誤處理
    die("刪除失敗: " . $e->getMessage());
}