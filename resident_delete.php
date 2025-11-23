<?php
include("db.php");

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM residents WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: resident_list.php"); 
    exit;
} else {
    echo "刪除失敗: " . $stmt->error;
}
