<?php
if (isset($_GET['url']) && !empty($_GET['url'])) {
    
    $url = $_GET['url'];
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $query = urlencode($query);
    
    switch ($url) {
        case "https://yandex.ru":
            $searchUrl = "https://yandex.ru/search/?text=" . $query;
            break;
        case "https://google.com":
            $searchUrl = "https://www.google.com/search?q=" . $query;
            break;
        case "https://mail.ru":
            $searchUrl = "https://go.mail.ru/search?q=" . $query;
            break;
        case "https://bing.com":
            $searchUrl = "https://www.bing.com/search?q=" . $query;
            break;
        case "https://duckduckgo.com":
            $searchUrl = "https://duckduckgo.com/?q=" . $query;
            break;
        default:
            $searchUrl = $url;
    }
    
    header("Location: $searchUrl");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ошибка</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e6e6e6;
            padding: 20px;
        }
        .error-box {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="error-box">
        <h1>Ошибка</h1>
        <p>Не указан адрес поисковика!</p>
        <a href="index.php">← Вернуться на главную</a>
    </div>
</body>
</html>
