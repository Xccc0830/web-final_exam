<?php
// checkin_insert.php (PDO 轉換版本)

require_once("db.php");
// 這裡可以加上 Admin 權限檢查

$resident_id = $_GET['resident_id'] ?? 0;

if (!$resident_id || !is_numeric($resident_id)) {
    header("Location: resident_list.php?error=invalid_resident");
    exit;
}

try {
    // 準備 INSERT 語句 (PDO 預備語句)
    $stmt = $pdo->prepare("INSERT INTO checkins (resident_id, checkin_time) VALUES (?, NOW())");
    
    // 執行並綁定參數
    $stmt->execute([$resident_id]);

    if ($stmt->rowCount() > 0) {
        header("Location: checkin_list.php?resident_id=$resident_id&msg=add_success");
        exit;
    } else {
        header("Location: checkin_list.php?resident_id=$resident_id&error=insert_failed");
        exit;
    }
} catch (PDOException $e) {
    // 資料庫錯誤處理
    die("新增失敗: " . $e->getMessage());
}