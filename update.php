<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/gallery.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo json_encode(['success' => false]);
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($id <= 0 || !in_array($action, ['view', 'like'])) {
    echo json_encode(['success' => false]);
    exit;
}

if ($action === 'view') {
    $pdo->prepare("UPDATE images SET views = views + 1 WHERE id = ?")->execute([$id]);
    $views = $pdo->prepare("SELECT views FROM images WHERE id = ?")->execute([$id]) ? $pdo->query("SELECT views FROM images WHERE id = $id")->fetchColumn() : 0;
    echo json_encode(['success' => true, 'views' => $views]);
}

if ($action === 'like') {
    $pdo->prepare("UPDATE images SET likes = likes + 1 WHERE id = ?")->execute([$id]);
    $likes = $pdo->prepare("SELECT likes FROM images WHERE id = ?")->execute([$id]) ? $pdo->query("SELECT likes FROM images WHERE id = $id")->fetchColumn() : 0;
    echo json_encode(['success' => true, 'likes' => $likes]);
}
?>
