<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/gallery.db');

$columns = $pdo->query("PRAGMA table_info(images)")->fetchAll(PDO::FETCH_ASSOC);

echo "<h3>Структура таблицы images:</h3>";
foreach ($columns as $col) {
    echo $col['name'] . " — " . $col['type'] . "<br>";
}

$data = $pdo->query("SELECT id, original_name, views, likes FROM images")->fetchAll();
echo "<h3>Текущие данные:</h3>";
foreach ($data as $row) {
    echo "ID {$row['id']}: {$row['original_name']} — 👁️ {$row['views']} | ❤️ {$row['likes']}<br>";
}
?>
