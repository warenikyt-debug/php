<?php
echo "<!DOCTYPE html>";
echo "<html><head><meta charset='UTF-8'><title>Установка</title>";
echo "<style>body{font-family:Arial;background:#e6e6e6;padding:20px;}.box{max-width:600px;margin:0 auto;background:white;padding:30px;border-radius:10px;}</style>";
echo "</head><body><div class='box'>";
echo "<h1>Установка галереи</h1>";

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/gallery.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Подключение к SQLite создано</p>";
    
    $sql = "CREATE TABLE IF NOT EXISTS images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        filename TEXT NOT NULL,
        original_name TEXT NOT NULL,
        filepath TEXT NOT NULL,
        thumbnail_path TEXT NOT NULL,
        filesize INTEGER NOT NULL,
        width INTEGER,
        height INTEGER,
        views INTEGER DEFAULT 0,
        likes INTEGER DEFAULT 0,
        upload_date DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "<p style='color:green;'>✅ Таблица 'images' создана</p>";
    
    // Проверяем наличие полей views и likes
    $columns = $pdo->query("PRAGMA table_info(images)")->fetchAll(PDO::FETCH_ASSOC);
    $hasViews = false;
    $hasLikes = false;
    
    foreach ($columns as $col) {
        if ($col['name'] == 'views') $hasViews = true;
        if ($col['name'] == 'likes') $hasLikes = true;
    }
    
    if (!$hasViews) {
        $pdo->exec("ALTER TABLE images ADD COLUMN views INTEGER DEFAULT 0");
        echo "<p>✅ Добавлено поле views</p>";
    }
    
    if (!$hasLikes) {
        $pdo->exec("ALTER TABLE images ADD COLUMN likes INTEGER DEFAULT 0");
        echo "<p>✅ Добавлено поле likes</p>";
    }
    
    $count = $pdo->query("SELECT COUNT(*) FROM images")->fetchColumn();
    echo "<p>📸 Фотографий в базе: " . $count . "</p>";
    
    echo "<p><a href='gallery.php' style='display:inline-block;padding:10px 20px;background:#4CAF50;color:white;text-decoration:none;border-radius:5px;'>Перейти в галерею</a></p>";
    
} catch(PDOException $e) {
    echo "<p style='color:red;'>❌ Ошибка: " . $e->getMessage() . "</p>";
}

echo "</div></body></html>";
?>
