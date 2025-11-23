<?php
include("db.php");

$id = $_GET['id'] ?? 0;
$resident_id = $_GET['resident_id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM violations WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: violation_list.php?resident_id=$resident_id");
    exit;
} else {
    echo "刪除失敗: " . $stmt->error;
}
