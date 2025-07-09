<?php
session_start();
include("db.php");

if (!isset($_SESSION["email"])) {
    header("Location: index.html");
    exit();
}

$email = $_SESSION["email"];
$content = $_POST["content"];
$tag = $_POST["tag"];

$stmt = $conn->prepare("INSERT INTO diary_entries (email, content, tag) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $content, $tag);
$stmt->execute();

header("Location: diary.php");
?>
