<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/gallery.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Создаем таблицу отзывов
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    author TEXT NOT NULL,
    text TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK(rating >= 1 AND rating <= 5),
    helpful INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

$pdo->exec($sql);
echo "✅ Таблица 'reviews' создана<br>";

// Проверяем структуру
$columns = $pdo->query("PRAGMA table_info(reviews)")->fetchAll();
echo "<h3>Структура таблицы reviews:</h3>";
foreach ($columns as $col) {
    echo $col['name'] . " — " . $col['type'] . "<br>";
}

echo "<p><a href='reviews.php'>Перейти к отзывам</a></p>";
?>
