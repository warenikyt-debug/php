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

// Добавим тестовые отзывы для демонстрации
$count = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
if ($count == 0) {
    $testReviews = [
        ['Иван', 'Отличный калькулятор! Очень удобно считать сложные функции.', 5],
        ['Мария', 'Функция Аккермана работает, но медленно для больших чисел.', 4],
        ['Петр', 'Хороший проект, всё понятно и наглядно.', 5],
        ['Анна', 'Галерея отличная, лайки работают!', 5],
        ['Дмитрий', 'Можно добавить больше математических функций.', 3],
    ];
    
    $stmt = $pdo->prepare("INSERT INTO reviews (author, text, rating) VALUES (?, ?, ?)");
    foreach ($testReviews as $review) {
        $stmt->execute($review);
    }
    echo "✅ Добавлено " . count($testReviews) . " тестовых отзывов<br>";
}

// Проверяем статистику
$avgAll = $pdo->query("SELECT AVG(rating) as avg FROM reviews")->fetch(PDO::FETCH_ASSOC);
$total = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
echo "<h3>Статистика:</h3>";
echo "<p>Всего отзывов: $total</p>";
echo "<p>Средняя оценка: " . round($avgAll['avg'] ?? 0, 1) . "</p>";

echo "<p><a href='reviews.php'>Перейти к отзывам</a></p>";
?>
