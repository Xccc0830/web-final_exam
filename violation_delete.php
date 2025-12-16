<?php
// resident_delete.php (PDO 轉換版本)

require_once("db.php");
// 這裡可以加上 Admin 權限檢查

$id = $_GET['id'] ?? 0;

if (!$id || !is_numeric($id)) {
    die("缺少住民 ID。");
}

try {
    // 準備 DELETE 語句 (PDO 預備語句)
    $stmt = $pdo->prepare("DELETE FROM residents WHERE id=?");
    
    // 執行並綁定參數
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        // 刪除成功
        header("Location: resident_list.php?msg=delete_success"); 
        exit;
    } else {
        // ID 不存在
        header("Location: resident_list.php?error=not_found");
        exit;
    }
} catch (PDOException $e) {
    // 資料庫錯誤 (例如外鍵約束：如果該住民有違規或簽到紀錄，則無法刪除)
    die("刪除失敗: 資料庫錯誤: " . $e->getMessage());
}