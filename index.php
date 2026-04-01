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
        h2 {
            font-size: 1.5em;
            color: #4CAF50;
        }
        .nav-links {
            display: flex;
            justify-content: space-around;
            margin: 20px 0 30px 0;
        }
        .nav-links a {
            display: inline-block;
            padding: 12px 25px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .nav-links a:hover {
            background: #45a049;
        }
        .nav-links a.gallery {
            background: #2196F3;
        }
        .nav-links a.gallery:hover {
            background: #0b7dda;
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
            font-weight: bold;
        }
        button:hover {
            background: #45a049;
        }
        .search-links {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Калькулятор -->
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
                
                <label>Выберите операцию:</label>
                <select name="operation" required>
                    <option value="">-- Выберите --</option>
                    <option value="add">Сложение (+)</option>
                    <option value="subtract">Вычитание (-)</option>
                    <option value="multiply">Умножение (*)</option>
                    <option value="divide">Деление (/)</option>
                    <option value="ackermann">Функция Аккермана A(n,m)</option>
                    <option value="combinations">Число сочетаний C(n,m)</option>
                    <option value="a_function">Функция a(n)</option>
                    <option value="logarithm">Логарифм logₐ(b)</option>
                    <option value="derivative">Производная xⁿ</option>
                </select>
                
                <button type="submit">Вычислить</button>
            </form>
        </div>
        
        <!-- Поисковик -->
        <div class="search">
            <h2>🔍 Быстрый поиск</h2>
            
            <div class="search-links">
                <a href="search.php?url=https://yandex.ru&query=">Яндекс</a>
                <a href="search.php?url=https://google.com&query=">Google</a>
                <a href="search.php?url=https://mail.ru&query=">Mail.ru</a>
                <a href="search.php?url=https://bing.com&query=">Bing</a>
            </div>
            
            <div class="search-form">
                <form method="GET" action="search.php">
                    <label>Выберите поисковик:</label>
                    <select name="url" required>
                        <option value="">-- Выберите --</option>
                        <option value="https://yandex.ru">Яндекс</option>
                        <option value="https://google.com">Google</option>
                        <option value="https://mail.ru">Mail.ru</option>
                        <option value="https://bing.com">Bing</option>
                        <option value="https://duckduckgo.com">DuckDuckGo</option>
                    </select>
                    
                    <label>Поисковый запрос:</label>
                    <input type="text" name="query" placeholder="Введите запрос..." required>
                    
                    <button type="submit">🔍 Искать</button>
                </form>
            </div>
            
            <hr>
            <h3>Примеры запросов:</h3>
            <div class="search-links">
                <a href="search.php?url=https://yandex.ru&query=какая погода сегодня">Погода (Яндекс)</a>
                <a href="search.php?url=https://google.com&query=новости">Новости (Google)</a>
                <a href="search.php?url=https://mail.ru&query=кино">Кино (Mail.ru)</a>
            </div>
        </div>
    </div>
</body>
</html>
