<?php
include("db.php");

$student_id = $_POST['student_id'];
$name = $_POST['name'];
$room = $_POST['room'];
$phone = $_POST['phone'];

$stmt = $conn->prepare("INSERT INTO residents (student_id, name, room, phone) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $student_id, $name, $room, $phone);

if ($stmt->execute()) {
    header("Location: resident_list.php"); 
    exit;
} else {
    echo "新增失敗: " . $stmt->error;
}
