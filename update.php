<?php
// update.php (PDO 統一版本)
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得資料
    $id = $_POST['id'] ?? 0;
    $student_id = $_POST['student_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $room = $_POST['room'] ?? '';
    $phone = $_POST['phone'] ?? '';

    // 基本欄位檢查
    if (empty($id) || empty($student_id) || empty($name) || empty($room)) {
        header("Location: resident_edit.php?id=$id&error=missing_fields"); 
        exit;
    }

    try {
        // 準備 SQL 更新語句
        $sql = "UPDATE residents SET student_id = ?, name = ?, room = ?, phone = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$student_id, $name, $room, $phone, $id])) {
            header("Location: resident_list.php?msg=update_success"); 
            exit;
        }
    } catch (PDOException $e) {
        // 捕捉重複學號錯誤 (SQL 錯誤碼 23000)
        if ($e->getCode() == '23000') {
             header("Location: resident_edit.php?id=$id&error=student_id_exists");
             exit;
        }
        die("更新失敗: " . $e->getMessage());
    }
}
?>