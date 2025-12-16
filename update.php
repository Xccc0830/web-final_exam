<?php
// update.php (PDO 轉換版本)

require_once("db.php");
// 這裡可以加上 Admin 權限檢查

// 取得 POST 數據
$id = $_POST['id'] ?? 0;
$student_id = $_POST['student_id'] ?? '';
$name = $_POST['name'] ?? '';
$room = $_POST['room'] ?? '';
$phone = $_POST['phone'] ?? '';

if (!$id || empty($student_id) || empty($name) || empty($room)) {
    die("資料不完整。");
}

try {
    // 準備 UPDATE 語句 (PDO 預備語句)
    $sql = "UPDATE residents SET student_id=?, name=?, room=?, phone=? WHERE id=?";
    
    $stmt = $pdo->prepare($sql);
    
    // 執行並綁定參數 (順序對應 SQL 中的 ?)
    $stmt->execute([$student_id, $name, $room, $phone, $id]);

    // 檢查是否成功更新
    // 注意：如果資料未變動，rowCount() 可能為 0，但我們仍視為操作成功並導向列表
    header("Location: resident_list.php?msg=update_success"); 
    exit;

} catch (PDOException $e) {
    // 資料庫錯誤處理
    die("更新失敗: " . $e->getMessage());
}