<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Калькулятор и Поиск</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6e6e6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
        }
        .calculator, .search {
            background: white;
            padding: 30px;
            border-radius: 10px;
            border: 2px solid #cccccc;
            margin-bottom: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333333;
        }
        .nav-links {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
        }
        .nav-links a {
            padding: 12px 25px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .nav-links a.gallery {
            background: #2196F3;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #999;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-links {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            flex-wrap: wrap;
            gap: 10px;
        }
        .search-links a {
            padding: 10px 20px;
            background: #f0f0f0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #999;
        }
        .search-links a:hover {
            background: #4CAF50;
            color: white;
        }
        .search-form {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        hr {
            margin: 20px 0;
        }
        .method-badge {
            display: inline-block;
            background: #ff9800;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- КАЛЬКУЛЯТОР -->
        <div class="calculator">
            <h1>🧮 Калькулятор</h1>
            <div class="nav-links">
                <a href="gallery.php" class="gallery">🖼️ Галерея</a>
                <a href="upload.php">📤 Загрузить фото</a>
            </div>
            <hr>
            <form method="POST" action="calculator.php">
                <label>Первое число:</label>
                <input type="number" name="num1" step="any" placeholder="Введите число" required>
                <label>Второе число:</label>
                <input type="number" name="num2" step="any" placeholder="Введите число" required>
                <label>Операция:</label>
                <select name="operation" required>
                    <option value="">-- Выберите --</option>
                    <option value="add">Сложение (+)</option>
                    <option value="subtract">Вычитание (-)</option>
                    <option value="multiply">Умножение (*)</option>
                    <option value="divide">Деление (/)</option>
                    <option value="ackermann">Функция Аккермана</option>
                    <option value="combinations">Число сочетаний</option>
                    <option value="a_function">Функция a(n)</option>
                    <option value="logarithm">Логарифм</option>
                    <option value="derivative">Производная xⁿ</option>
                </select>
                <button type="submit">Вычислить</button>
            </form>
        </div>

        <!-- ПОИСКОВИК: GET и POST -->
        <div class="search">
            <h2>🔍 Быстрый поиск</h2>

            <!-- GET-запросы: ссылки -->
            <h3>📌 GET-запрос (ссылки)</h3>
            <div class="search-links">
                <a href="search.php?url=https://yandex.ru&query=погода">Яндекс: погода</a>
                <a href="search.php?url=https://google.com&query=новости">Google: новости</a>
                <a href="search.php?url=https://mail.ru&query=кино">Mail.ru: кино</a>
                <a href="search.php?url=https://bing.com&query=AI">Bing: AI</a>
            </div>

            <!-- GET-запрос: форма с GET -->
            <div class="search-form">
                <h3>📌 GET-запрос (форма) <span class="method-badge">GET</span></h3>
                <form method="GET" action="search.php">
                    <label>Поисковик:</label>
                    <select name="url" required>
                        <option value="https://yandex.ru">Яндекс</option>
                        <option value="https://google.com">Google</option>
                        <option value="https://mail.ru">Mail.ru</option>
                        <option value="https://bing.com">Bing</option>
                    </select>
                    <label>Запрос:</label>
                    <input type="text" name="query" placeholder="Введите запрос..." required>
                    <button type="submit">🔍 Искать (GET)</button>
                </form>
            </div>

            <!-- POST-запрос: форма с POST, которая перенаправляет на GET -->
            <div class="search-form">
                <h3>📌 POST-запрос → перенаправление <span class="method-badge">POST</span></h3>
                <form method="POST" action="search_post.php">
                    <label>Поисковик:</label>
                    <select name="url" required>
                        <option value="https://yandex.ru">Яндекс</option>
                        <option value="https://google.com">Google</option>
                        <option value="https://mail.ru">Mail.ru</option>
                        <option value="https://bing.com">Bing</option>
                    </select>
                    <label>Запрос:</label>
                    <input type="text" name="query" placeholder="Введите запрос..." required>
                    <button type="submit">🔍 Искать (POST)</button>
                </form>
                <p style="font-size:12px; color:#666; margin-top:5px;">
                    ⚡ POST-запрос отправляется на сервер, затем перенаправляет на GET-URL поисковика
                </p>
            </div>

            <hr>
            <h3>📌 Примеры GET-запросов (как в poisk.html)</h3>
            <div class="search-links">
                <a href="search.php?url=https://yandex.ru&query=какая погода сегодня">Погода (Яндекс)</a>
                <a href="search.php?url=https://google.com&query=курс доллара">Курс доллара (Google)</a>
            </div>
        </div>
    </div>
</body>
</html>
