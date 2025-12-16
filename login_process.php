<?php
// login_process.php - 已加入 role 欄位處理

session_start();
require_once 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $student_id = $conn->real_escape_string($_POST['student_id']);
    $password = $conn->real_escape_string($_POST['password']); 
    
    // -----------------------------------------------------------------
    // 關鍵調整：SELECT 中加入 role 欄位
    // -----------------------------------------------------------------
    $sql = "SELECT id, student_id, name, role FROM residents WHERE student_id = ? AND password = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        $_SESSION['login_error'] = '系統錯誤：無法準備查詢。';
        header("location: login.php");
        exit;
    }
    
    $stmt->bind_param("ss", $student_id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $resident = $result->fetch_assoc();
        
        // -----------------------------------------------------------------
        // 關鍵調整：將 role 儲存到 Session
        // -----------------------------------------------------------------
        $_SESSION['role'] = $resident['role']; 
        
        // 登入成功
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['resident_id'] = $resident['id']; 
        $_SESSION['name'] = $resident['name'];
        $_SESSION['student_id'] = $resident['student_id'];
        
        header("location: dashboard.php");
        exit; 
            
    } else {
        // 找不到匹配的學號和密碼組合 (錯誤)
        $_SESSION['login_error'] = "登入失敗：學號或密碼錯誤。";
        $_SESSION['temp_student_id'] = $student_id;
        header("location: login.php");
        exit;
    }

    $stmt->close();
}

$conn->close();
?>