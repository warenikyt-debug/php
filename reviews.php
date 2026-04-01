<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/gallery.db');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Обработка добавления отзыва
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_review') {
        $author = trim($_POST['author'] ?? '');
        $text = trim($_POST['text'] ?? '');
        $rating = (int)($_POST['rating'] ?? 0);
        
        if (empty($author)) {
            $error = "Введите имя автора";
        } elseif (empty($text)) {
            $error = "Введите текст отзыва";
        } elseif ($rating < 1 || $rating > 5) {
            $error = "Оценка должна быть от 1 до 5";
        } else {
            $stmt = $pdo->prepare("INSERT INTO reviews (author, text, rating) VALUES (?, ?, ?)");
            if ($stmt->execute([$author, $text, $rating])) {
                $success = "✅ Отзыв добавлен!";
            } else {
                $error = "❌ Ошибка при добавлении";
            }
        }
    }
    
    // Обработка полезности (лайка отзыва)
    if ($_POST['action'] === 'helpful' && isset($_POST['review_id'])) {
        $id = (int)$_POST['review_id'];
        $pdo->prepare("UPDATE reviews SET helpful = helpful + 1 WHERE id = ?")->execute([$id]);
        header("Location: reviews.php");
        exit;
    }
}

// Получаем статистику
$avgAll = $pdo->query("SELECT AVG(rating) as avg FROM reviews")->fetch(PDO::FETCH_ASSOC);
$avgAll = round($avgAll['avg'] ?? 0, 1);

// Получаем последние 10 отзывов
$recent = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

// Получаем популярные (по полезности и оценке)
$popular = $pdo->query("SELECT * FROM reviews ORDER BY helpful DESC, rating DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

// Получаем отзывы с высокой оценкой
$topRated = $pdo->query("SELECT * FROM reviews WHERE rating >= 4 ORDER BY rating DESC, helpful DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);

// Получаем медианную оценку
$ratings = $pdo->query("SELECT rating FROM reviews ORDER BY rating")->fetchAll(PDO::FETCH_COLUMN);
$median = 0;
if (!empty($ratings)) {
    $count = count($ratings);
    $middle = floor($count / 2);
    if ($count % 2 == 0) {
        $median = ($ratings[$middle - 1] + $ratings[$middle]) / 2;
    } else {
        $median = $ratings[$middle];
    }
}
$median = round($median, 1);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Отзывы</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #e6e6e6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .nav {
            text-align: center;
            margin: 20px 0;
        }
        .nav a {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
        }
        .stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            min-width: 150px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #4CAF50;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .form-container, .reviews-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 2px solid #ccc;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #999;
            border-radius: 5px;
            font-size: 16px;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        .rating-stars {
            display: flex;
            gap: 10px;
            font-size: 30px;
            cursor: pointer;
        }
        .rating-star {
            color: #ddd;
            transition: color 0.2s;
        }
        .rating-star.active {
            color: #ffc107;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background: #45a049;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .success {
            background: #d4edda;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .review-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #4CAF50;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .review-author {
            font-weight: bold;
            color: #333;
        }
        .review-date {
            font-size: 12px;
            color: #999;
        }
        .review-rating {
            color: #ffc107;
            font-size: 18px;
            margin: 10px 0;
        }
        .review-text {
            color: #555;
            line-height: 1.5;
            margin: 10px 0;
        }
        .review-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .helpful-btn {
            background: none;
            border: none;
            color: #4CAF50;
            cursor: pointer;
            font-size: 14px;
            padding: 5px 10px;
        }
        .helpful-btn:hover {
            background: #e8f5e9;
        }
        .helpful-count {
            color: #666;
            font-size: 12px;
        }
        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }
        .tab-btn {
            background: #f0f0f0;
            color: #333;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .tab-btn.active {
            background: #4CAF50;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Отзывы о калькуляторе</h1>
        
        <div class="nav">
            <a href="index.php">← Калькулятор</a>
            <a href="gallery.php">🖼️ Галерея</a>
            <a href="upload.php">📤 Загрузить фото</a>
        </div>
        
        <!-- Статистика -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?= count($ratings) ?></div>
                <div class="stat-label">Всего отзывов</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $avgAll ?></div>
                <div class="stat-label">Средняя оценка (все)</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $median ?></div>
                <div class="stat-label">Медианная оценка</div>
            </div>
        </div>
        
        <!-- Форма добавления отзыва -->
        <div class="form-container">
            <h2>✍️ Оставить отзыв</h2>
            
            <?php if ($success): ?>
                <div class="message success"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="message error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="hidden" name="action" value="add_review">
                
                <div class="form-group">
                    <label>Ваше имя:</label>
                    <input type="text" name="author" required placeholder="Введите имя">
                </div>
                
                <div class="form-group">
                    <label>Оценка:</label>
                    <div class="rating-stars" id="ratingStars">
                        <span class="rating-star" data-value="1">★</span>
                        <span class="rating-star" data-value="2">★</span>
                        <span class="rating-star" data-value="3">★</span>
                        <span class="rating-star" data-value="4">★</span>
                        <span class="rating-star" data-value="5">★</span>
                    </div>
                    <input type="hidden" name="rating" id="ratingValue" value="5">
                </div>
                
                <div class="form-group">
                    <label>Текст отзыва:</label>
                    <textarea name="text" required placeholder="Поделитесь впечатлениями..."></textarea>
                </div>
                
                <button type="submit">📝 Отправить отзыв</button>
            </form>
        </div>
        
        <!-- Вкладки с отзывами -->
        <div class="reviews-section">
            <div class="tab-buttons">
                <button class="tab-btn active" data-tab="recent">🕒 Последние</button>
                <button class="tab-btn" data-tab="popular">🔥 Популярные</button>
                <button class="tab-btn" data-tab="toprated">⭐ Топ-оценки</button>
            </div>
            
            <!-- Последние отзывы -->
            <div id="tab-recent" class="tab-content active">
                <h2>🕒 Последние отзывы</h2>
                <?php if (empty($recent)): ?>
                    <p style="text-align:center; color:#999;">Пока нет отзывов. Будьте первым!</p>
                <?php else: ?>
                    <div class="reviews-grid">
                        <?php foreach ($recent as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <span class="review-author"><?= htmlspecialchars($review['author']) ?></span>
                                    <span class="review-date"><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></span>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?= $i <= $review['rating'] ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="review-text"><?= nl2br(htmlspecialchars($review['text'])) ?></div>
                                <div class="review-footer">
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="action" value="helpful">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <button type="submit" class="helpful-btn">👍 Полезно (<?= $review['helpful'] ?>)</button>
                                    </form>
                                    <span class="helpful-count"></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Популярные отзывы -->
            <div id="tab-popular" class="tab-content">
                <h2>🔥 Популярные отзывы</h2>
                <?php if (empty($popular)): ?>
                    <p style="text-align:center; color:#999;">Пока нет отзывов</p>
                <?php else: ?>
                    <div class="reviews-grid">
                        <?php foreach ($popular as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <span class="review-author"><?= htmlspecialchars($review['author']) ?></span>
                                    <span class="review-date"><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></span>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?= $i <= $review['rating'] ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="review-text"><?= nl2br(htmlspecialchars($review['text'])) ?></div>
                                <div class="review-footer">
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="action" value="helpful">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <button type="submit" class="helpful-btn">👍 Полезно (<?= $review['helpful'] ?>)</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Топ-оценки -->
            <div id="tab-toprated" class="tab-content">
                <h2>⭐ Отзывы с высокой оценкой</h2>
                <?php if (empty($topRated)): ?>
                    <p style="text-align:center; color:#999;">Пока нет отзывов с оценкой 4+</p>
                <?php else: ?>
                    <div class="reviews-grid">
                        <?php foreach ($topRated as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <span class="review-author"><?= htmlspecialchars($review['author']) ?></span>
                                    <span class="review-date"><?= date('d.m.Y H:i', strtotime($review['created_at'])) ?></span>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <?= $i <= $review['rating'] ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                </div>
                                <div class="review-text"><?= nl2br(htmlspecialchars($review['text'])) ?></div>
                                <div class="review-footer">
                                    <form method="POST" style="margin:0;">
                                        <input type="hidden" name="action" value="helpful">
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        <button type="submit" class="helpful-btn">👍 Полезно (<?= $review['helpful'] ?>)</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Звездочки для оценки
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('ratingValue');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                ratingInput.value = value;
                
                stars.forEach((s, i) => {
                    if (i < value) {
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                    }
                });
            });
        });
        
        // Устанавливаем значение по умолчанию
        ratingInput.value = 5;
        stars.forEach((s, i) => {
            if (i < 5) s.classList.add('active');
        });
        
        // Вкладки
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;
                
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                document.getElementById('tab-' + tab).classList.add('active');
            });
        });
    </script>
</body>
</html>
