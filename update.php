<?php
include("db.php");

$id = $_POST['id'];
$student_id = $_POST['student_id'];
$name = $_POST['name'];
$room = $_POST['room'];
$phone = $_POST['phone'];

$stmt = $conn->prepare("UPDATE residents SET student_id=?, name=?, room=?, phone=? WHERE id=?");
$stmt->bind_param("ssssi", $student_id, $name, $room, $phone, $id);

if ($stmt->execute()) {
    header("Location: resident_list.php"); 
    exit;
} else {
    echo "更新失敗: " . $stmt->error;
}
